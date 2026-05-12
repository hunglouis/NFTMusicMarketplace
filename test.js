console.log("🚀 PREPARE FACTORY");

console.log(
  "NETWORK =",
  await provider.getNetwork()
);

console.log(
  "FACTORY =",
  FACTORY_ADDRESS
);

console.log(
  "SIGNER =",
  await signer.getAddress()
);

const factory = new ethers.Contract(
  FACTORY_ADDRESS,
  factoryAbi,
  signer
);

console.log("✅ FACTORY READY");

await window.ethereum.request({
  method: "eth_requestAccounts",
});

console.log("🚀 CALL createCollection");

const tx = await factory.createCollection(
  collectionName,
  collectionSymbol,
  {
    gasLimit: 3000000
  }
);

console.log("✅ TX CREATED");

console.log("TX HASH =", tx.hash);

setStatus("⛓️ Đang deploy collection...");

const receipt = await tx.wait();

console.log("✅ RECEIPT =", receipt);