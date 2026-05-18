// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/utils/ReentrancyGuard.sol";
import "@openzeppelin/contracts/token/ERC721/IERC721.sol";
import "@openzeppelin/contracts/token/common/ERC2981.sol";

contract NFTMarketplace is ReentrancyGuard {

    struct Listing {
        address seller;
        uint256 price;
        bool active;
    }

    // nftContract => tokenId => Listing
    mapping(address => mapping(uint256 => Listing)) public listings;

    event Listed(address nft, uint256 tokenId, uint256 price, address seller);
    event Sold(address nft, uint256 tokenId, address buyer, uint256 price);
    event Cancelled(address nft, uint256 tokenId);

    // 🟢 LIST NFT
    function listItem(address nftContract, uint256 tokenId, uint256 price) external {
        IERC721 nft = IERC721(nftContract);

        require(nft.ownerOf(tokenId) == msg.sender, "Not owner");
        require(price > 0, "Price must be > 0");

        // approve marketplace trước
        require(
            nft.getApproved(tokenId) == address(this),
            "Marketplace not approved"
        );

        listings[nftContract][tokenId] = Listing(
            msg.sender,
            price,
            true
        );

        emit Listed(nftContract, tokenId, price, msg.sender);
    }

    // 🔵 BUY NFT
function buyItem(address nftContract, uint256 tokenId)
    external
    payable
    nonReentrant
{
    Listing memory item = listings[nftContract][tokenId];

    require(item.active, "Not for sale");
    require(msg.value >= item.price, "Not enough ETH");

    IERC721 nft = IERC721(nftContract);

    address seller = item.seller;
    address buyer = msg.sender;

    // 💰 royalty check (EIP-2981)
    (address royaltyReceiver, uint256 royaltyAmount) =
        ERC2981(nftContract).royaltyInfo(tokenId, item.price);

    uint256 sellerAmount = item.price - royaltyAmount;

    // pay royalty
    if (royaltyAmount > 0) {
        (bool royaltySuccess, ) = royaltyReceiver.call{value: royaltyAmount}("");
        require(royaltySuccess, "Royalty transfer failed");
    }
    (bool sellerSuccess, ) = seller.call{value: sellerAmount}("");
    require(sellerSuccess, "Seller transfer failed");

    // transfer NFT
    nft.safeTransferFrom(seller, buyer, tokenId);

    // update listing
    listings[nftContract][tokenId].active = false;

    emit Sold(nftContract, tokenId, buyer, item.price);
}
                     // 🔴 CANCEL LISTING
 function cancelListing(address nftContract, uint256 tokenId) external {
        Listing memory item = listings[nftContract][tokenId];

        require(item.seller == msg.sender, "Not seller");

        listings[nftContract][tokenId].active = false;

        emit Cancelled(nftContract, tokenId);
    }
}