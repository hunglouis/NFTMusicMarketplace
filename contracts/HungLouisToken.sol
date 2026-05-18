// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

// IMPORT THƯ VIỆN ERC20 CHUẨN
import "@openzeppelin/contracts/token/ERC20/ERC20.sol";

contract HungLouisToken is ERC20 {

    // OWNER CỦA TOKEN
    address public owner;

    // KHI DEPLOY:
    // - TẠO TÊN TOKEN
    // - TẠO KÝ HIỆU TOKEN
    // - MINT TỔNG CUNG BAN ĐẦU
    constructor() ERC20("Hung Louis Token", "HLT") {

        owner = msg.sender;

        // 10 TRIỆU TOKEN
        // decimals() mặc định = 18
        _mint(
            msg.sender,
            10000000 * 10 ** decimals()
        );
    }

    // HÀM MINT THÊM TOKEN
    // CHỈ OWNER ĐƯỢC GỌI
    function mint(
        address to,
        uint256 amount
    ) public {

        require(
            msg.sender == owner,
            "Only owner"
        );

        _mint(to, amount);
    }

    // HÀM ĐỐT TOKEN
    function burn(
        uint256 amount
    ) public {

        _burn(msg.sender, amount);
    }
}