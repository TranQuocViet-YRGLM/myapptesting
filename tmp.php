<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiot Thông Tin Không Gian Văn Hóa Hồ Chí Minh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #d63384; 
        }
        .bg-custom-gradient {
            background: linear-gradient(rgba(214, 51, 132, 0.8), rgba(214, 51, 132, 0.9)), 
                        url('https://images.unsplash.com/photo-1596401057633-54a8fe8ef647?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .qr-card {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 260px;
        }
        .qr-card:hover {
            transform: scale(1.05);
        }
        .glass-nav {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
        }
        /* Style cho Carousel */
        #carousel-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
            gap: 2rem;
        }
        .carousel-item {
            flex: 0 0 calc(33.333% - 1.35rem);
            display: flex;
            justify-content: center;
        }

        /* Hiệu ứng chữ chạy (Marquee) */
        .marquee-container {
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 0;
            margin-top: 2rem;
        }
        .marquee-content {
            display: inline-block;
            animation: scroll-left 25s linear infinite;
            padding-left: 100%;
        }
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .marquee-content a {
            color: white;
            margin: 0 30px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .marquee-content a:hover {
            color: #fce7f3; /* Pink-100 */
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .carousel-item {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col">

    <!-- Lớp phủ Background -->
    <div class="fixed inset-0 bg-custom-gradient -z-10"></div>

    <!-- Header -->
    <header class="p-4 md:p-6 flex flex-col items-center w-full text-white">
        <!-- Thông tin đơn vị (Nằm trên cùng và canh giữa) -->
        <div class="text-center mb-4 opacity-90">
          <p class="text-[22px] md:text-[28px] font-semibold uppercase leading-tight tracking-wider">
              UỶ BAN NHÂN DÂN PHƯỜNG TĂNG NHƠN PHÚ
          </p>
          <p class="text-[22px] md:text-[28px] font-semibold uppercase leading-tight tracking-wider">
              TRƯỜNG THCS HIỆP PHÚ
          </p>
      </div>

        <!-- Khối Logo và Tiêu đề Kiot (Nằm dưới và căn lề hài hòa) -->
        <div class="flex items-center justify-center gap-4 md:gap-8 w-full max-w-5xl border-t border-white/20 pt-4">
            <div class="bg-white p-1 rounded-full shadow-lg shrink-0">
                <img src="image/logo-thcs-hiep-phu-2025-tron_2920252358.jpg" alt="Logo Trường THCS Hiệp Phú" class="w-12 h-12 md:w-20 md:h-20 rounded-full object-contain" onerror="this.src='https://via.placeholder.com/64'">
            </div>
            <h1 class="text-xl md:text-3xl font-bold uppercase leading-tight tracking-wide">
                KIOT THÔNG TIN KHÔNG GIAN VĂN HÓA HỒ CHÍ MINH
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center px-4 relative overflow-hidden">
        
        <!-- Nút điều hướng trái -->
        <button onclick="moveCarousel(-1)" class="absolute left-4 md:left-10 text-white/50 hover:text-white transition z-20">
            <i data-lucide="chevron-left" class="w-10 h-10 md:w-16 md:h-16"></i>
        </button>

        <!-- QR Container -->
        <div class="w-full max-w-6xl overflow-hidden px-4">
            <div id="carousel-wrapper">
                <div class="carousel-item">
                    <div class="qr-card border-4 border-green-500 text-center">
                        <img src="image/TuLieuVeBac.jpg" alt="Tư liệu về Bác" class="w-full aspect-square object-cover">
                        <span class="text-sm">Tư liệu về Bác</span>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="qr-card border-4 border-green-500 text-center">
                        <img src="image/BacHovoiThieuNhi.jpg" alt="Bác Hồ với thiếu nhi" class="w-full aspect-square object-cover">
                        <span class="text-sm">Bác Hồ với thiếu nhi</span>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="qr-card border-4 border-green-500 text-center">
                        <img src="image/CactacphamcuaBac.jpg" alt="Các tác phẩm của Bác" class="w-full aspect-square object-cover">
                        <span class="text-sm">Các tác phẩm của Bác</span>
                    </div>
                </div>
                 <div class="carousel-item">
                    <div class="qr-card border-4 border-green-500 text-center">
                        <img src="image/KeChuyenBacHoToNguVan.jpg" alt="Kể chuyện Bác Hồ Tổ Ngữ Văn" class="w-full aspect-square object-cover">
                        <span class="text-sm">Kể chuyện Bác Hồ Tổ Ngữ Văn</span>
                    </div>
                </div>
                
                 <div class="carousel-item">
                    <div class="qr-card border-4 border-green-500 text-center">
                        <img src="image/KeChuyenBacHoToToan.jpg" alt="Kể chuyện Bác Hồ Tổ Toán" class="w-full aspect-square object-cover">
                        <span class="text-sm">Kể chuyện Bác Hồ Tổ Toán</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Nút điều hướng phải -->
        <button onclick="moveCarousel(1)" class="absolute right-4 md:right-10 text-white/50 hover:text-white transition z-20">
            <i data-lucide="chevron-right" class="w-10 h-10 md:w-16 md:h-16"></i>
        </button>

        <!-- Nút Trung tâm -->
        <div class="mt-12 text-center">
          <a href="https://docs.google.com/document/d/1kdNPFbdWsQXfO5OanSxTfYRESfJVObZV/edit?usp=drive_link&ouid=105931398485849076457&rtpof=true&sd=true" class="px-8 py-3 rounded-full border-2 border-white/50 bg-white/10 hover:bg-white/20 text-white text-xl font-medium backdrop-blur-sm transition-all duration-300" target="_blank">
                Phim tư liệu
            </a>
            <a href="https://docs.google.com/document/d/1kdNPFbdWsQXfO5OanSxTfYRESfJVObZV/edit?usp=drive_link&ouid=105931398485849076457&rtpof=true&sd=true" class="px-8 py-3 rounded-full border-2 border-white/50 bg-white/10 hover:bg-white/20 text-white text-xl font-medium backdrop-blur-sm transition-all duration-300" target="_blank">
                Tư Liệu về Bác
            <a href="https://docs.google.com/document/d/1Me_brLH8DPfvXgEOIARmYXUi8Dn7T7UQ/edit?usp=drive_link&ouid=105931398485849076457&rtpof=true&sd=true" class="px-8 py-3 rounded-full border-2 border-white/50 bg-white/10 hover:bg-white/20 text-white text-xl font-medium backdrop-blur-sm transition-all duration-300" target="_blank">
                Kể chuyện Bác Hồ
            </a>
            <a href="https://drive.google.com/file/d/1uUAubrgLjNEdHkG9nhbf51JsxEiSqB_I/view?usp=sharing" class="px-8 py-3 rounded-full border-2 border-white/50 bg-white/10 hover:bg-white/20 text-white text-xl font-medium backdrop-blur-sm transition-all duration-300" target="_blank">
                Bản đồ hành trình cứu nước
            </a>
            <a href="https://docs.google.com/document/d/1tJeXDoSzWeGvAfooqnwWqN7es2Nq-UX8/edit?usp=drive_link&ouid=105931398485849076457&rtpof=true&sd=true" class="px-8 py-3 rounded-full border-2 border-white/50 bg-white/10 hover:bg-white/20 text-white text-xl font-medium backdrop-blur-sm transition-all duration-300" target="_blank">
                Khen thưởng Hồ Chí Minh
            </a>
        </div>

        <!-- Dải chữ chạy liên kết -->
        <div class="marquee-container">
            <div class="marquee-content">
                <a href="https://thcshiepphu.hcm.edu.vn/homegd1413" target="_blank">Cổng thông tin điện tử Trường THCS Hiệp Phú</a>
                <a href="https://thcs-hiepphu-tangnhonphu.hcm.dlib.vn/app/login?redirect=/" target="_blank">Thư viện điện tử THCS Hiệp Phú</a>
            </div>
        </div>
    </main>

    <!-- Footer Navigation -->
    <footer class="glass-nav py-4 px-6">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-around gap-4 text-white">
            <a href="https://drive.google.com/file/d/1db2hTXtT0c9ncOaqaqA293oKQ_Bwz6Rf/view?usp=sharing" target="_blank" class="flex items-center gap-2 group">
                <div class="p-2 bg-pink-500 rounded-lg group-hover:bg-pink-400 transition">
                    <i data-lucide="info" class="w-5 h-5"></i>
                </div>
                <span class="text-sm md:text-base font-medium">Giới thiệu Không gian Văn hóa</span>
            </a>
            <a href="https://www.canva.com/design/DAG1rh7SkrQ/vU0x_nFrUB317vURKbTDyA/edit" target="_blank" class="flex items-center gap-2 group">
                <div class="p-2 bg-blue-500 rounded-lg group-hover:bg-blue-400 transition">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <span class="text-sm md:text-base font-medium">Tư liệu về Chủ tịch Hồ Chí Minh</span>
            </a>
            <a href="https://docs.google.com/document/d/1kdNPFbdWsQXfO5OanSxTfYRESfJVObZV/edit?usp=drive_link&ouid=105931398485849076457&rtpof=true&sd=true" target="_blank" class="flex items-center gap-2 group">
                <div class="p-2 bg-yellow-500 rounded-lg group-hover:bg-yellow-400 transition">
                    <i data-lucide="bookmark" class="w-5 h-5"></i>
                </div>
                <span class="text-sm md:text-base font-medium">Sách hay về Bác Hồ</span>
            </a>
            <a href="https://docs.google.com/presentation/d/1iJlcxoxA0GUqL3hLycGm2jPORZsU0a53/edit?usp=sharing&ouid=102302011306427736658&rtpof=true&sd=truenh" target="_blank" class="flex items-center gap-2 group">
                <div class="p-2 bg-purple-500 rounded-lg group-hover:bg-purple-400 transition">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-sm md:text-base font-medium">Hoạt động học tập</span>
            </a>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        let currentIndex = 0;
        const wrapper = document.getElementById('carousel-wrapper');
        const items = document.querySelectorAll('.carousel-item');
        
        function moveCarousel(direction) {
            const itemsToShow = window.innerWidth > 768 ? 3 : 1;
            const maxIndex = items.length - itemsToShow;
            
            currentIndex += direction;

            if (currentIndex < 0) currentIndex = 0;
            if (currentIndex > maxIndex) currentIndex = maxIndex;

            const gap = 32; 
            const itemWidth = items[0].offsetWidth;
            const moveAmount = currentIndex * (itemWidth + gap);

            wrapper.style.transform = `translateX(-${moveAmount}px)`;
        }

        window.addEventListener('resize', () => {
            currentIndex = 0;
            wrapper.style.transform = 'translateX(0)';
        });
    </script>
</body>
</html>