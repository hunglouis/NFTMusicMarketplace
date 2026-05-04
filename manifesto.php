<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-neon { color: #00ffff; text-shadow: 0 0 10px rgba(0,255,255,0.5); }
    </style>
</head>
<body class="bg-black text-gray-200 font-sans leading-relaxed">
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
    <!-- BỘ CHỌN NGÔN NGỮ CÓ LÁ CỜ -->
<div class="flex justify-center gap-6 mb-10 py-4 border-y border-white/10">
    <button onclick="changeLang('vi')" id="btn-vi" style="color:#00ffff; font-weight:bold;">🇻🇳 TIẾNG VIỆT</button>
    <button onclick="changeLang('en')" id="btn-en" style="color:#6b7280; font-weight:bold;">🇺🇸 ENGLISH</button>
    <button onclick="changeLang('fr')" id="btn-fr" style="color:#6b7280; font-weight:bold;">🇫🇷 FRANÇAIS</button>
    <button onclick="changeLang('zh')" id="btn-zh" style="color:#6b7280; font-weight:bold;">🇨🇳 中文</button>
</div>



<!-- TỔ HỢP XÁC THỰC SMART CONTRACT & HƯỚNG DẪN MINH BẠCH -->
<div class="max-w-6xl mx-auto mb-16 space-y-6">
    
    <!-- 1. Thanh địa chỉ hợp đồng -->
    <div class="glass-card border-cyan-500/50 p-1 rounded-2xl bg-gradient-to-r from-cyan-950/20 to-transparent">
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-[0_0_10px_#22c55e]"></div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Trạng thái hệ thống</p>
                    <p class="text-white font-mono text-sm">Smart Contract Đã Xác Minh (Verified)</p>
                </div>
            </div>
            
            <div class="flex flex-col md:items-end">
                <p class="text-[10px] text-gray-500 mb-1 uppercase font-bold tracking-widest">Địa chỉ hợp đồng (Polygon):</p>
                <a href="https://polygonscan.com" 
                   target="_blank" 
                   class="text-neon font-mono text-sm hover:underline flex items-center gap-2 group">
                    <span>0xddcd6cf61e81c6e22a082841a388a19984ff4745</span>
                    <svg xmlns="http://w3.org" class="h-4 w-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Khối Hướng dẫn soi Contract cho người mới -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card p-6 rounded-3xl border-white/5 bg-white/5">
            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                <span class="text-neon">🔍</span> Hướng dẫn kiểm tra minh bạch
            </h4>
            <div class="space-y-4 text-xs text-gray-400">
                <div class="flex gap-3">
                    <span class="text-neon font-mono">01.</span>
                    <p id="step-01" >Click vào link <b>Polygonscan</b> ở trên để mở sổ cái công khai của hệ thống.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">02.</span>
                    <p id="step-02" >Chọn tab <b>"Contract"</b> -> <b>"Read Contract"</b> để xem các quy tắc đã lập trình.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">03.</span>
                    <p id="step-03" >Tại mục <b>"royaltyInfo"</b>: Bạn sẽ thấy tỷ lệ 10% (1000) - Cam kết bảo tồn vĩnh viễn.</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl border-white/5 bg-white/5">
            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                <span class="text-neon">🛡️</span> Quyền lợi đối chiếu thực tế
            </h4>
            <div class="space-y-4 text-xs text-gray-400">
                <div class="flex gap-3">
                    <span class="text-neon font-mono">04.</span>
                    <p id="step-04" >Tại mục <b>"ownerOf"</b>: Nhập TokenID bạn giữ để thấy ví của mình đã được ghi danh.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">05.</span>
                    <p id="step-05" >Mọi thông tin tại đây là <b>vĩnh viễn</b>, ngân hàng hay tác giả đều không thể tự ý sửa đổi.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">06.</span>
                    <p id="step-06" >Dữ liệu này là bằng chứng pháp lý cao nhất cho quyền sở hữu của bạn.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="max-w-6xl mx-auto px-6 py-12">
    <header class="text-center mb-16">
        <h1 class="text-5xl font-black text-neon mb-4 tracking-tighter uppercase">路易斯音乐宣言 / HUNGLOUIS MUSIC MANIFESTO</h1>
        <p class="text-xl text-gray-400 mb-8">Hệ thống vận hành & Bảo tồn di sản âm nhạc số</p>
        <div class="max-w-4xl mx-auto glass-card p-8 rounded-[2rem] border-cyan-500/30">
            <p id="manifesto-text" class="text-lg italic text-white...">
                "Âm nhạc không chỉ là những nốt nhạc, đó là di sản của tâm hồn. HungLouis Music ra đời để bảo tồn những độc bản, tôn vinh người sáng tạo và xây dựng cộng đồng sở hữu trí tuệ minh bạch."
            </p>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
         <!-- CỘT TRÁI: MỤC LỤC -->
        <section class="space-y-4">
            <div class="glass-card p-8 rounded-[2rem] sticky top-8">
                <h3 class="text-white font-bold mb-6 border-b border-white/10 pb-2 uppercase tracking-widest text-sm">Mục lục</h3>
                <nav class="flex flex-col gap-4 text-sm text-gray-400">
                    <a href="#chuong-1" class="hover:text-neon">Chương 1: Ý niệm nghệ thuật</a>
                    <a href="#chuong-2" class="hover:text-neon">Chương 2: Cẩm nang Độc bản (721)</a>
                    <a href="#chuong-3" class="hover:text-neon">Chương 3: Đặc quyền Cộng đồng (1155)</a>
                    <a href="#chuong-4" class="hover:text-neon">Chương 4: Chống bào mòn (2981)</a>
                </nav>
            </div>
        </section>

        <!-- Main Content Area -->
        <main class="md:col-span-2 space-y-16">
            <!-- Chương 1 -->
            <section id="chuong-1" class="glass-card p-8 rounded-[2rem]">
                <h2 id="c1-title"class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span class="text-neon text-2xl">01</span> Chương 1: Ý niệm nghệ thuật
                </h2>
                <p id="c1-desc" class="text-gray-400 leading-relaxed">
                    Chúng tôi coi âm nhạc là <b>Di sản</b> thay vì hàng hóa tiêu dùng. Louis Music tập hợp những tâm hồn muốn bảo vệ tính nguyên bản của nghệ thuật trước sự nhân bản vô tính của kỷ nguyên số.
                </p>
            </section>
            
            <!-- Chương 2 (Như trong hình bạn gửi) -->
            <section id="chuong-2" class="glass-card p-8 rounded-[2rem]">
                <h2 id="c2-title"class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span id="c2-lead" class="text-neon text-2xl">02</span> Chương 2: Vận hành Độc bản
                </h2>
                <div class="space-y-6">
                    <div>
                        <h4 id="c2-h1" class="text-neon font-bold">2.1. Xác thực nguồn gốc</h4>
                        <p id="c2-p1" class="text-sm text-gray-400">Mỗi NFT ERC-721 được đúc duy nhất một bản. Bạn có thể kiểm tra "Provenance" trên Polygonscan để đảm bảo chữ ký số từ Louis Music.</p>
                    </div>
                    <div>
                        <h4 id="c2-h2" class="text-neon font-bold">2.2. Quyền Giám hộ</h4>
                        <p id="c2-p2" class="text-sm text-gray-400">Chủ sở hữu có quyền thương lượng bản quyền phái sinh và sử dụng tác phẩm cho mục đích thương mại theo thỏa thuận đặc biệt.</p>
                    </div>
                </div>
            </section>

            <!-- Chương 3 (BỔ SUNG) -->
            <section id="chuong-3" class="glass-card p-8 rounded-[2rem]">
                <h2 id="c3-title" class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span id="c3-lead" class="text-neon text-2xl">03</span> Chương 3: Đặc quyền Cộng đồng (ERC-1155)    
                </h2>
                <ul id="c3-lead"class="space-y-3 text-sm text-gray-400">
                    <li id="c3-li-1"class="flex items-start gap-2"><span>✔️</span> Quyền truy cập thư viện âm thanh Lossless và video hậu trường.</li>
                    <li id="c3-li-2"class="flex items-start gap-2"><span>✔️</span> Nhận Airdrop các bản demo đặc biệt trực tiếp vào ví cá nhân.</li>
                    <li id="c3-li-3" class="flex items-start gap-2"><span>✔️</span> Tham gia biểu quyết (DAO) cho các hoạt động cộng đồng.</li>
                    <li id="c3-li-4" class="flex items-start gap-2"><span>✔️</span> Ưu tiên mua các bộ sưu tập giới hạn trong tương lai.</li>
                </ul>
            </section>
                        <!-- PHẦN NÀY ĐÃ CÓ ĐỦ VĂN BẢN - BẠN CHỈ CẦN COPY & PASTE -->
            <section id="chuong-4" class="glass-card p-8 rounded-[2rem]">            
                <h2 id="c4-title" class="text-3xl font-bold text-white mb-6 flex items-center gap-3">
                    <span id="c4-lead"class="text-neon text-2xl">04</span> Chương 4: Chống bào mòn tài sản
                </h2>
                <ul class="list-disc pl-5 text-sm text-gray-400 ">
                    <li id="c4-li-1">Tại Louis Music, chúng tôi chấm dứt kỷ nguyên của các loại phí ẩn. 
                    Khác với hệ thống ngân hàng truyền thống - nơi biểu phí có thể thay đổi đơn phương 
                    và âm thầm bào mòn tài sản của khách hàng - mọi quy tắc tại đây được bảo vệ bởi toán học.</li>
                    <li id="c4-li-2">Phí tác quyền 10% được lập trình cứng trong Smart Contract. Không một ai (kể cả tác giả) có quyền tự ý nâng mức phí sau khi phát hành.</li>
                    <li id="c4-li-3">Phí này dùng để bảo tồn di sản, đảm bảo giá trị NFT của bạn luôn tăng trưởng.</li>
                </ul>
                </section>

                        <!-- BOX HIỆU TRIỆU XÁC THỰC -->
            <div class="max-w-4xl mx-auto my-12 p-8 rounded-[3rem] border-2 border-dashed border-cyan-500/30 bg-cyan-950/10 text-center">
                <h3 class="text-2xl font-black text-white mb-4">BẠN KHÔNG CẦN PHẢI TIN CHÚNG TÔI</h3>
                <p class="text-gray-400 mb-6 px-4">
                    Khác với các tổ chức tài chính truyền thống luôn giữ kín các thuật toán thu phí, 
                    <span class="text-neon">Louis Music</span> mở toang cánh cửa để bạn giám sát. 
                    Hãy để toán học và Blockchain chứng minh quyền lợi của bạn.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#huong-dan-soi" class="px-8 py-3 bg-cyan-500 text-black font-bold rounded-full hover:scale-105 transition">
                        Cách "soi" Contract cho người mới
                    </a>
                    <a href="https://polygonscan.com" target="_blank" class="px-8 py-3 border border-cyan-500 text-cyan-500 font-bold rounded-full hover:bg-cyan-500/10 transition">
                        Xem trực tiếp trên Polygonscan
                    </a>
                </div>
            </div>

        </main>
        
    </div>
</div>
<script>
function changeLang(lang) {
    const data = {
        vi: {
            manifesto: '"Âm nhạc không chỉ là những nốt nhạc, đó là di sản của tâm hồn. HungLouis Music ra đời để bảo tồn những độc bản, tôn vinh người sáng tạo và xây dựng cộng đồng sở hữu trí tuệ minh bạch."',
            c1_t: 'Chương 1: Ý niệm nghệ thuật',
            c1_d: 'Tại Louis Music, mỗi bản nhạc không phải là một sản phẩm tiêu dùng nhanh. Chúng tôi coi đó là "Di sản số".',
            c2_t: 'Chương 2: Cẩm nang Độc bản (ERC-721)',
            c2_h1: '2.1. Quyền Giám hộ',
            c2_p1: 'Người sở hữu NFT 721 là người duy nhất nắm giữ "Linh hồn" của tác phẩm vĩnh viễn trên Blockchain.',
            c2_h2: '2.2. Khai thác thương mại',
            c2_p2: 'Chủ sở hữu có quyền thương lượng lợi ích khi tác phẩm được sử dụng trong phim ảnh hoặc quảng cáo.',
            c3_t: 'Chương 3: Quyền lợi Cộng đồng (ERC-1155)',
            c3_l: 'Sở hữu NFT 1155 là nắm giữ "Mảnh ghép" di sản và chìa khóa vào hệ sinh thái:',
            c3_1: '✔️ Truy cập thư viện âm thanh chất lượng cao (Lossless).',
            c3_2: '✔️ Nhận quà tặng Airdrop âm nhạc định kỳ từ Nghệ sĩ.',
            c3_3: '✔️ Quyền ưu tiên mua các bộ sưu tập giới hạn.',
            c3_4: '✔️ Tham gia biểu quyết (DAO) cho các hoạt động cộng đồng.',
            s1: 'Click vào link Polygonscan ở trên để mở sổ cái công khai.',
            s2: 'Chọn tab "Contract" -> "Read Contract" để xem quy tắc.',
            s3: 'Tại mục "royaltyInfo": Tỷ lệ 10% - Cam kết vĩnh viễn.',
            s4: 'Tại mục "ownerOf": Nhập TokenID để thấy ví mình.',
            s5: 'Thông tin vĩnh viễn, ngân hàng không thể tự ý sửa đổi.',
            s6: 'Bằng chứng pháp lý cao nhất cho quyền sở hữu của bạn.',
            c4_t: 'Chương 4: Chống bào mòn tài sản',
            c4_d: 'Chúng tôi chấm dứt kỷ nguyên phí ẩn. Khác với ngân hàng, mọi quy tắc tại đây được bảo vệ bởi toán học.',
            c4_n: 'QUY TẮC BẤT BIẾN:.',
            c4_1: 'Phí 10% được lập trình cứng trong Smart Contract.',
            c4_2: 'Không ai có quyền nâng phí sau khi đã phát hành NFT.',
            c4_3: 'Phí dùng để bảo tồn di sản và gia tăng giá trị NFT.'
            // ... (Phần Chương 4 và 6 bước soi đã có ở trên)
            },
        en: {
            manifesto: '"Music is more than notes; it is the heritage of the soul. HungLouis Music was born to preserve originals, honor creators, and build a transparent intellectual property community."',
            c1_t: 'Chapter 1: The Artistic Concept',
            c1_d: 'At Louis Music, each piece is not a consumer good. We consider it "Digital Heritage".',
            c2_t: 'Chapter 2: The Master Guide (ERC-721)',
            c2_h1: '2.1. Heritage Guardianship',
            c2_p1: 'The 721 NFT owner is the sole guardian of the work\'s "Soul" permanently on the Blockchain.',
            c2_h2: '2.2. Commercial Exploitation',
            c2_p2: 'Owners have the right to negotiate benefits when used in films or commercials.',
            c3_t: 'Chapter 3: Community Privileges (ERC-1155)',
            c3_l: 'Owning an 1155 NFT is holding a "Legacy Piece" and a key to the ecosystem:',
            c3_1: '✔️ Access to the high-quality (Lossless) audio library.',
            c3_2: '✔️ Receive periodic music Airdrops from the Artist.',
            c3_3: '✔️ Priority rights to purchase limited collections.',
            c3_4: '✔️ Participate in voting (DAO) for community activities.',
            s1: 'Click the Polygonscan link above to open the public ledger.',
            s2: 'Select "Contract" -> "Read Contract" to view rules.',
            s3: 'Under "royaltyInfo": 10% Rate - A perpetual commitment.',
            s4: 'Under "ownerOf": Enter TokenID to see your wallet.',
            s5: 'Permanent info; banks cannot modify it unilaterally.',
            s6: 'The highest legal proof of your digital ownership.',
            c4_t: 'Chapter 4: Asset Erosion Protection',
            c4_d:'We end the era of hidden fees. Unlike banks, every rule here is protected by mathematics.',
            c4_n:'UNCHANGEABLE RULES:',
            c4_1:'10% royalty fee is hard-coded into the Smart Contract.',
            c4_2:'No one can increase fees after the NFT launch.',
            c4_3:'Fees are used to preserve heritage and grow NFT value.'
             },
        fr: {
            manifesto: '"La musique est plus que des notes ; c\'est l\'héritage de l\'âme. HungLouis Music est né pour préserver les originaux, honorer les créateurs et bâtir une communauté transparente."',
            c1_t: 'Chapitre 1: Le Concept Artistique',
            c1_d: 'Chez HungLouis Music, chaque morceau n\'est pas un produit de consommation. Nous le considérons comme un "Patrimoine Numérique".',
            c2_t: 'Chapitre 2: Le Guide du Maître (ERC-721)',
            c2_h1: '2.1. Tutelle du Patrimoine',
            c2_p1: 'Le détenteur du NFT 721 est le tuteur exclusif de "l\'Âme" de l\'œuvre de façon permanente sur la Blockchain.',
            c2_h2: '2.2. Exploitation Commerciale',
            c2_p2: 'Les propriétaires ont le droit de négocier des avantages lorsque l\'œuvre est utilisée dans des films ou publicités.',
            c3_t: 'Chapitre 3: Privilèges Communautaires (ERC-1155)',
            c3_l: 'Posséder un NFT 1155, c\'est détenir une "Pièce d\'Héritage" et une clé de l\'écosystème:',
            c3_1: '✔️ Accès à la bibliothèque audio haute qualité (Lossless).',
            c3_2: '✔️ Recevoir des Airdrops musicaux périodiques de l\'Artiste.',
            c3_3: '✔️ Droits prioritaires pour l\'achat de collections limitées.',
            c3_4: '✔️ Participer aux votes (DAO) pour les activités de la communauté.',
            s1: 'Cliquez sur le lien Polygonscan ci-dessus pour ouvrir le registre public.',
            s2: 'Sélectionnez "Contract" -> "Read Contract" pour voir les règles.',
            s3: 'Sous "royaltyInfo": Taux de 10% - Un engagement perpétuel.',
            s4: 'Sous "ownerOf": Entrez le TokenID pour voir votre portefeuille.',
            s5: 'Informations permanentes; les banques ne peuvent pas les modifier unilatéralement.',
            s6: 'La preuve légale la plus élevée de votre propriété numérique.',
            c4_t: 'Chapitre 4: Protection contre l\'érosion des actifs',
            c4_d: 'Nous mettons fin à l\'ère des frais cachés. Contrairement aux banques, chaque règle ici est protégée par les mathématiques.',
            c4_n: 'RÈGLES INCHANGEABLES:',
            c4_1: 'La redevance de 10% est codée en dur dans le Smart Contract.',
            c4_2: 'Personne ne peut augmenter les frais après le lancement du NFT.',
            c4_3: 'Les frais sont utilisés pour préserver le patrimoine et augmenter la valeur du NFT.'
            },
        zh: {
            manifesto: '"音乐不仅仅是音符，它是灵魂的遗产。路易斯音乐 (HungLouis Music) 为保护原创、致敬创作者并构建透明的知识产权社区而生。"',
            c1_t: '第一章：艺术理念',
            c1_d: '在路易斯音乐，每一首曲子都不是快消品。我们视其为“数字遗产”。我们的目标是在数字时代重振真实艺术的价值。',
            c2_t: '第二章：独家指南 (ERC-721)',
            c2_h1: '2.1. 遗产监护权',
            c2_p1: '721 NFT 持有者是该作品“灵魂”在区块链账本上的永久唯一监护人。',
            c2_h2: '2.2. 商业开发权',
            c2_p2: '当作品被用于电影或广告时，持有者有权进行谈判并获得利益。',
            c3_t: '第三章：社区特权 (ERC-1155)',
            c3_l: '拥有 1155 NFT 即是持有“遗产碎片”和进入生态系统的钥匙：',
            c3_1: '✔️ 访问高质量 (Lossless) 音频库。',
            c3_2: '✔️ 定期接收艺术家发送的音乐空投。',
            c3_3: '✔️ 限量系列的优先购买权。',
            c3_4: '✔️ 参与社区活动的投票表决 (DAO)。',
            s1: '✔️ 参与社区活动的投票表决 (DAO)。',
            s2: '✔️ 参与社区活动的投票表决 (DAO)。',
            s3: '✔️ 参与社区活动的投票表决 (DAO)。',
            s4: '✔️ 参与社区活动的投票表决 (DAO)。',
            s5: '✔️ 参与社区活动的投票表决 (DAO)。',
            s6: '✔️ 参与社区活动的投票表决 (DAO)。',
            c4_t: '第四章：资产侵蚀保护',
            c4_d: '我们结束了隐藏费用的时代。与银行不同，这里的每一条规则都受到数学的保护。',
            c4_n: '不可更改的规则：',
            c4_1: '10%的版税费用被硬编码到智能合约中。',
            c4_2: 'NFT发布后，任何人都不能提高费用。',
            c4_3: '费用用于保护遗产并提升NFT价值。'
        }
    };
// Lệnh thực thi thay đổi (Cần bọc trong try-catch để nếu thiếu 1 ID cũng không bị đơ cả trang)
    try {
        document.getElementById('manifesto-text').innerText = data[lang].manifesto;
        document.getElementById('c1-title').innerText = data[lang].c1_t;
        document.getElementById('c1-desc').innerText = data[lang].c1_d;
        document.getElementById('c2-title').innerText = data[lang].c2_t;
        document.getElementById('c2-lead').innerText = data[lang].c2_lead;
        document.getElementById('c2-h1').innerText = data[lang].c2_h1;
        document.getElementById('c2-p1').innerText = data[lang].c2_p1;
        document.getElementById('c2-h2').innerText = data[lang].c2_h2;
        document.getElementById('c2-p2').innerText = data[lang].c2_p2;
        document.getElementById('c3-title').innerText = data[lang].c3_t;
        document.getElementById('c3-lead').innerText = data[lang].c3_l;
        document.getElementById('c3-li-1').innerText = data[lang].c3_1;
        document.getElementById('c3-li-2').innerText = data[lang].c3_2;
        document.getElementById('c3-li-3').innerText = data[lang].c3_3;
        document.getElementById('c3-li-4').innerText = data[lang].c3_4;
        document.getElementById('c4-title').innerText = data[lang].c4_t;
        document.getElementById('c4-desc').innerText = data[lang].c4_d;
        document.getElementById('c4-lead').innerText = data[lang].c4_n;        
        document.getElementById('c4-note').innerText = data[lang].c4_n;
        document.getElementById('c4-li-1').innerText = data[lang].c4_1;
        document.getElementById('c4-li-2').innerText = data[lang].c4_2;
        document.getElementById('c4-li-3').innerText = data[lang].c4_3;

        for (let i = 1; i <= 6; i++) {
            let el = document.getElementById('step-0' + i);
            if(el) el.innerText = data[lang]['s' + i];
        }

        // Đổi màu nút bấm
        ['vi', 'en', 'fr', 'zh'].forEach(l => {
            let btn = document.getElementById('btn-' + l);
            if(btn) btn.style.color = (l === lang) ? '#00ffff' : '#6b7280';
        });
    } catch (e) {
        console.log("Lỗi: Thiếu ID trong HTML", e);
    }
}
</script>


</body>
</html>
