// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC721/extensions/ERC721URIStorage.sol";

contract StudioNFT is ERC721URIStorage {

    uint256 private _nextTokenId;

    address public creator;

    event NFTMinted(
        address indexed collectionAddress,
        uint256 indexed tokenId,
        address indexed owner,
        string uri,
        uint256 timestamp
    );

    constructor(
        string memory name,
        string memory symbol,
        address _creator
    )
        ERC721(name, symbol)
    {
        creator = _creator;
    }

    function mintNFT(
        address to,
        string memory uri
    )
        public
        returns(uint256)
    {
        require(msg.sender == creator, "Not creator");

        uint256 tokenId = _nextTokenId;

        _nextTokenId++;

        _safeMint(to, tokenId);

        _setTokenURI(tokenId, uri);

        emit NFTMinted(
            address(this),
            tokenId,
            to,
            uri,
            block.timestamp
        );

        return tokenId;
    }

    function totalSupply() public view returns(uint256) {
        return _nextTokenId;
    }
}