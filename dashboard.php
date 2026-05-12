<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Thành Viên - Music NFT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .contract-item { border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; }
        .btn-mint { background: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

<div class="card">
    <h2>🎵 Dashboard Của Bạn</h2>
    <p>Ví đang kết nối: <span id="0x8429BC345266D03a433b25B8Fb6301274294D81E">Chưa kết nối</span></p>
    <h3>Bộ sưu tập của bạn đã sở hữu:</h3>
    <div id="0xeaE543209f1Cdb5758BE84DebdFe003bD85121C9">Đang tìm kiếm hợp đồng trên Blockchain...</div>
</div>

<script>
const FACTORY_ADDRESS = "0x96BBA5cCC21236f869A0D3F05720F607220eE33F";
const FACTORY_ABI = [  	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "_implementation", 				"type": "address" 			} 		], 		"stateMutability": "nonpayable", 		"type": "constructor" 	}, 	{ 		"inputs": [], 		"name": "FailedDeployment", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "balance", 				"type": "uint256" 			}, 			{ 				"internalType": "uint256", 				"name": "needed", 				"type": "uint256" 			} 		], 		"name": "InsufficientBalance", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "string", 				"name": "name", 				"type": "string" 			}, 			{ 				"internalType": "string", 				"name": "symbol", 				"type": "string" 			} 		], 		"name": "createNewCollection", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "", 				"type": "uint256" 			} 		], 		"name": "deployedCollections", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [], 		"name": "implementation", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}];
const COLLECTION_ABI = [ 	{ 		"inputs": [], 		"stateMutability": "nonpayable", 		"type": "constructor" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "sender", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			}, 			{ 				"internalType": "address", 				"name": "owner", 				"type": "address" 			} 		], 		"name": "ERC721IncorrectOwner", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "operator", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "ERC721InsufficientApproval", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "approver", 				"type": "address" 			} 		], 		"name": "ERC721InvalidApprover", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "operator", 				"type": "address" 			} 		], 		"name": "ERC721InvalidOperator", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "owner", 				"type": "address" 			} 		], 		"name": "ERC721InvalidOwner", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "receiver", 				"type": "address" 			} 		], 		"name": "ERC721InvalidReceiver", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "sender", 				"type": "address" 			} 		], 		"name": "ERC721InvalidSender", 		"type": "error" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "ERC721NonexistentToken", 		"type": "error" 	}, 	{ 		"anonymous": false, 		"inputs": [ 			{ 				"indexed": true, 				"internalType": "address", 				"name": "owner", 				"type": "address" 			}, 			{ 				"indexed": true, 				"internalType": "address", 				"name": "approved", 				"type": "address" 			}, 			{ 				"indexed": true, 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "Approval", 		"type": "event" 	}, 	{ 		"anonymous": false, 		"inputs": [ 			{ 				"indexed": true, 				"internalType": "address", 				"name": "owner", 				"type": "address" 			}, 			{ 				"indexed": true, 				"internalType": "address", 				"name": "operator", 				"type": "address" 			}, 			{ 				"indexed": false, 				"internalType": "bool", 				"name": "approved", 				"type": "bool" 			} 		], 		"name": "ApprovalForAll", 		"type": "event" 	}, 	{ 		"anonymous": false, 		"inputs": [ 			{ 				"indexed": true, 				"internalType": "address", 				"name": "from", 				"type": "address" 			}, 			{ 				"indexed": true, 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"indexed": true, 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "Transfer", 		"type": "event" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "approve", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "owner", 				"type": "address" 			} 		], 		"name": "balanceOf", 		"outputs": [ 			{ 				"internalType": "uint256", 				"name": "", 				"type": "uint256" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [], 		"name": "creator", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "getApproved", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "string", 				"name": "name", 				"type": "string" 			}, 			{ 				"internalType": "string", 				"name": "symbol", 				"type": "string" 			}, 			{ 				"internalType": "address", 				"name": "_creator", 				"type": "address" 			} 		], 		"name": "initialize", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "owner", 				"type": "address" 			}, 			{ 				"internalType": "address", 				"name": "operator", 				"type": "address" 			} 		], 		"name": "isApprovedForAll", 		"outputs": [ 			{ 				"internalType": "bool", 				"name": "", 				"type": "bool" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "mint", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [], 		"name": "name", 		"outputs": [ 			{ 				"internalType": "string", 				"name": "", 				"type": "string" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "ownerOf", 		"outputs": [ 			{ 				"internalType": "address", 				"name": "", 				"type": "address" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "from", 				"type": "address" 			}, 			{ 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "safeTransferFrom", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "from", 				"type": "address" 			}, 			{ 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			}, 			{ 				"internalType": "bytes", 				"name": "data", 				"type": "bytes" 			} 		], 		"name": "safeTransferFrom", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "operator", 				"type": "address" 			}, 			{ 				"internalType": "bool", 				"name": "approved", 				"type": "bool" 			} 		], 		"name": "setApprovalForAll", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "bytes4", 				"name": "interfaceId", 				"type": "bytes4" 			} 		], 		"name": "supportsInterface", 		"outputs": [ 			{ 				"internalType": "bool", 				"name": "", 				"type": "bool" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [], 		"name": "symbol", 		"outputs": [ 			{ 				"internalType": "string", 				"name": "", 				"type": "string" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "tokenURI", 		"outputs": [ 			{ 				"internalType": "string", 				"name": "", 				"type": "string" 			} 		], 		"stateMutability": "view", 		"type": "function" 	}, 	{ 		"inputs": [ 			{ 				"internalType": "address", 				"name": "from", 				"type": "address" 			}, 			{ 				"internalType": "address", 				"name": "to", 				"type": "address" 			}, 			{ 				"internalType": "uint256", 				"name": "tokenId", 				"type": "uint256" 			} 		], 		"name": "transferFrom", 		"outputs": [], 		"stateMutability": "nonpayable", 		"type": "function" 	}];
const MARKET_ADDR = "0x624dce1e5da13ad6f45e897280d1a3f8b36b4af3"; // ĐỊA CHỈ HỢP ĐỒNG MARKETPLACE BẠN VỪA DEPLOY

async function listHeritage(contractAddr, tokenId) {
    const price = prompt("Nhập giá niêm yết (MATIC):", "0.1");
    if (!price) return;

    try {
        const provider = new ethers.BrowserProvider(window.ethereum);
        const signer = await provider.getSigner();
        const userAddr = await signer.getAddress();

        // 1. APPROVE: Cho phép sàn quản lý NFT này
        const nftContract = new ethers.Contract(contractAddr, ["function setApprovalForAll(address operator, bool approved) public"], signer);
        console.log("Đang cấp quyền cho sàn...");
        const approveTx = await nftContract.setApprovalForAll(MARKET_ADDR, true);
        await approveTx.wait();

        // 2. LIST: Gửi lệnh lên Marketplace Contract
        const marketContract = new ethers.Contract(MARKET_ADDR, [
            "function listNFT(address _nftContract, uint256 _tokenId, uint256 _price) external"
        ], signer);
        
        const priceWei = ethers.parseEther(price);
        console.log("Đang đưa di sản lên sàn...");
        const listTx = await marketContract.listNFT(contractAddr, tokenId, priceWei);
        await listTx.wait();

        // 3. SYNC: Lưu vào bảng market_listings trên Supabase
        const { data, error } = await supabase.from('market_listings').insert([{
            contract_address: contractAddr,
            token_id: tokenId,
            seller_address: userAddr,
            price_matic: parseFloat(price),
            status: 'active'
        }]);

        if (error) throw error;

        alert("✅ NIÊM YẾT THÀNH CÔNG! Di sản của bạn đã lên kệ.");
        location.reload();

    } catch (err) {
        console.error(err);
        alert("❌ Lỗi niêm yết: " + err.message);
    }
}


async function initDashboard() {
    if (!window.ethereum) return alert("Hãy cài MetaMask");
    
    const provider = new ethers.BrowserProvider(window.ethereum);
    const signer = await provider.getSigner();
    const userAddress = await signer.getAddress();
    document.getElementById('walletAddress').innerText = userAddress;

    const factoryContract = new ethers.Contract(FACTORY_ADDRESS, FACTORY_ABI, provider);
    let html = "";
    let i = 0;

    // Quét toàn bộ nhà máy để tìm con của ví này
    while (true) {
        try {
            const contractAddr = await factoryContract.deployedCollections(i);
            const collectionContract = new ethers.Contract(contractAddr, COLLECTION_ABI, provider);
            
            // Kiểm tra xem ai là chủ hợp đồng này
            const creator = await collectionContract.creator();
            
            if (creator.toLowerCase() === userAddress.toLowerCase()) {
                const name = await collectionContract.name();
                html += `
                <div class="contract-item">
                    <span><strong>${name}</strong> (${contractAddr.substring(0,6)}...${contractAddr.substring(38)})</span>
                    <button class="btn-mint" onclick="window.location.href='mint_page.php?address=${contractAddr}'">Đúc NFT (Mint)</button>
                </div>`;
            }
            i++;
        } catch (e) { break; }
    }

    document.getElementById('myCollections').innerHTML = html || "Bạn chưa có bộ sưu tập nào. Hãy tạo mới!";
}

window.onload = initDashboard;
</script>

</body>
</html>
