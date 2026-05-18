async function addHLTToken() {

  try {

    await window.ethereum.request({
      method: 'wallet_watchAsset',
      params: {
        type: 'ERC20',
        options: {

          address: '0xBB99a71fDcC25B9AeFa25c8CfBD62C319c114FE1',

          symbol: 'HLT',

          decimals: 18,

          image:
'https://gateway.pinata.cloud/ipfs/bafybeigg3ivnrfcvpumqqxarm5roer7woaxl5ughxputkybxwd6i7rdnyu'

        },
      },
    });

  } catch (error) {

    console.log(error);

  }
}