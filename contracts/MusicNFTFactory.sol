// SPDX-License-Identifier: GPL-3.0
pragma solidity ^0.8.20;

import "./StudioNFT.sol";

contract MusicNFTFactory {

    event CollectionCreated(
        address indexed collectionAddress,
        string name,
        string symbol,
        address indexed creator,
        uint256 timestamp
    );

    address[] public allCollections;

    mapping(address => address[]) public creatorCollections;

    function createCollection(
        string memory _name,
        string memory _symbol
    ) public returns(address) {

        StudioNFT newCollection =
            new StudioNFT(
                _name,
                _symbol,
                msg.sender
            );

        address collectionAddress = address(newCollection);

        allCollections.push(collectionAddress);

        creatorCollections[msg.sender].push(collectionAddress);

        emit CollectionCreated(
            collectionAddress,
            _name,
            _symbol,
            msg.sender,
            block.timestamp
        );

        return collectionAddress;
    }

    function getAllCollections()
        public
        view
        returns(address[] memory)
    {
        return allCollections;
    }

    function getCollectionsByCreator(address user)
        public
        view
        returns(address[] memory)
    {
        return creatorCollections[user];
    }
}