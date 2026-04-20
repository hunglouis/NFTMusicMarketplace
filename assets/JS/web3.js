const CONTRACT = "0x254B57b096a308c97A90da781D0E4cd74a733f4D";

const ABI = [
    {
        "inputs":[{"name":"tokenId","type":"uint256"}],
        "name":"buy",
        "type":"function",
        "stateMutability":"payable"
    },
    {
        "inputs":[{"name":"","type":"uint256"}],
        "name":"musics",
        "outputs":[
            {"name":"tokenId","type":"uint256"},
            {"name":"price","type":"uint256"},
            {"name":"creator","type":"address"},
            {"name":"forSale","type":"bool"}
        ],
        "stateMutability":"view"
    },
    {
        "inputs":[{"name":"tokenId","type":"uint256"}],
        "name":"ownerOf",
        "outputs":[{"type":"address"}],
        "stateMutability":"view"
    },
    {
        "inputs":[{"name":"tokenId","type":"uint256"}],
        "name":"tokenURI",
        "outputs":[{"type":"string"}],
        "stateMutability":"view"
    },
    {
        "inputs":[],
        "name":"tokenCount",
        "outputs":[{"type":"uint256"}],
        "stateMutability":"view"
    }
];

// 🦊 CONNECT
async function connectWallet() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const accounts = await provider.send("eth_requestAccounts", []);
    return accounts[0];
}
