<?php
/**
 * Care IOC - Giao Diện Đăng Nhập Hệ Thống
 * Thư mục: template/admin/auth/login.php
 * Đăng nhập an toàn với Số Căn Cước Công Dân (CCCD 12 chữ số)
 */
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập Hệ Thống | Care IOC - Trung Tâm Điều Hành Y Tế Thông Minh</title>
  
  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          },
          colors: {
            brand: {
              50: '#f0f7ff',
              100: '#e0effe',
              200: '#b9ddfd',
              300: '#7cc2fb',
              400: '#36a3f7',
              500: '#0c87eb',
              600: '#026bc9',
              700: '#0355a2',
              800: '#074885',
              900: '#0c3d6e',
              950: '#082749',
            }
          }
        }
      }
    }
  </script>
  
  <!-- Phosphor Icons -->
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css" />
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
  
  <!-- jQuery & SweetAlert2 -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body {
      font-feature-settings: "cv02", "cv03", "cv04", "cv11";
      background: radial-gradient(130% 100% at 50% 0%, #ebf5ff 0%, #f8fafc 60%, #e2e8f0 100%);
      min-height: 100vh;
    }
    .login-card {
      box-shadow: 0 25px 50px -12px rgba(12, 135, 235, 0.15), 0 0 0 1px rgba(12, 135, 235, 0.08);
    }
    .pulse-glow {
      animation: pulseGlow 4s ease-in-out infinite;
    }
    @keyframes pulseGlow {
      0%, 100% { opacity: 0.4; transform: scale(1); }
      50% { opacity: 0.7; transform: scale(1.05); }
    }
  </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-6 lg:p-8 min-h-screen text-slate-800 relative overflow-x-hidden">

  <!-- Background Decorative Glowing Orbs -->
  <div class="absolute top-[-10%] left-[-5%] w-[450px] h-[450px] rounded-full bg-blue-300/30 blur-3xl pointer-events-none pulse-glow"></div>
  <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-cyan-300/25 blur-3xl pointer-events-none pulse-glow" style="animation-delay: 2s;"></div>

  <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center z-10">
    
    <!-- CỘT TRÁI: GIỚI THIỆU & THÔNG TIN HỆ THỐNG IOC -->
    <div class="lg:col-span-6 space-y-6 text-left hidden lg:block pr-4">
      <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-semibold">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
        <span>Hệ Thống Trực Tuyến 24/7 • Trung Tâm IOC</span>
      </div>

      <div class="space-y-3">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
            <i class="ph-bold ph-hospital text-2xl"></i>
          </div>
          <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none">CARE IOC</h1>
            <p class="text-xs text-brand-600 font-bold uppercase tracking-wider mt-1">Intelligent Operations Center</p>
          </div>
        </div>
        <p class="text-slate-600 text-sm leading-relaxed">
          Trung tâm điều hành và giám sát y tế thông minh toàn diện: Phân tích lâm sàng, cảnh báo cấp cứu, giám sát công suất giường bệnh và an toàn thông tin bệnh viện.
        </p>
      </div>

      <!-- Feature Badges -->
      <div class="grid grid-cols-2 gap-3 pt-2">
        <div class="p-3 rounded-2xl bg-white/80 border border-slate-200/80 shadow-sm backdrop-blur-sm">
          <div class="flex items-center gap-2.5 text-brand-600 mb-1">
            <i class="ph-bold ph-identification-card text-lg"></i>
            <span class="text-xs font-bold text-slate-800">Đăng Nhập CCCD</span>
          </div>
          <p class="text-[11px] text-slate-500 leading-tight">Xác thực chuẩn hóa theo Cơ sở dữ liệu Quốc gia về Dân cư.</p>
        </div>

        <div class="p-3 rounded-2xl bg-white/80 border border-slate-200/80 shadow-sm backdrop-blur-sm">
          <div class="flex items-center gap-2.5 text-emerald-600 mb-1">
            <i class="ph-bold ph-shield-check text-lg"></i>
            <span class="text-xs font-bold text-slate-800">Bảo Mật Cấp 3</span>
          </div>
          <p class="text-[11px] text-slate-500 leading-tight">Mã hóa an toàn dữ liệu y tế, nhật ký kiểm toán phiên làm việc.</p>
        </div>
      </div>

      <!-- Demo Accounts Card (Tiện ích cho kiểm thử nhanh) -->
      <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 border border-blue-100/90 text-xs">
        <div class="flex items-center justify-between mb-2">
          <span class="font-bold text-slate-700 flex items-center gap-1.5">
            <i class="ph-bold ph-lightning text-amber-500"></i>
            Tài khoản mẫu cán bộ (Bấm để tự điền):
          </span>
          <span class="text-[10px] text-slate-400 font-medium">Click để test</span>
        </div>
        <div class="space-y-1.5">
          <button type="button" onclick="fillAccount('037098045678', 'admin123')" class="w-full flex items-center justify-between p-2 rounded-xl bg-white border border-blue-100 hover:border-brand-500 hover:bg-brand-50/50 transition text-left group">
            <div>
              <strong class="text-slate-800 font-semibold group-hover:text-brand-600">KS. Vũ Xuân Cường</strong>
              <span class="text-[10px] text-slate-400 ml-1">(Quản trị DB/IOC)</span>
            </div>
            <span class="font-mono text-[11px] text-brand-600 bg-brand-50 px-2 py-0.5 rounded-lg border border-brand-100">037098045678</span>
          </button>

          <button type="button" onclick="fillAccount('001085012345', 'tuan123')" class="w-full flex items-center justify-between p-2 rounded-xl bg-white border border-blue-100 hover:border-brand-500 hover:bg-brand-50/50 transition text-left group">
            <div>
              <strong class="text-slate-800 font-semibold group-hover:text-brand-600">TS.BS Trần Quốc Tuấn</strong>
              <span class="text-[10px] text-slate-400 ml-1">(Trưởng khoa Tim Mạch)</span>
            </div>
            <span class="font-mono text-[11px] text-brand-600 bg-brand-50 px-2 py-0.5 rounded-lg border border-brand-100">001085012345</span>
          </button>

          <button type="button" onclick="fillAccount('001188023456', 'thao123')" class="w-full flex items-center justify-between p-2 rounded-xl bg-white border border-blue-100 hover:border-brand-500 hover:bg-brand-50/50 transition text-left group">
            <div>
              <strong class="text-slate-800 font-semibold group-hover:text-brand-600">ThS.BS Nguyễn Bích Thảo</strong>
              <span class="text-[10px] text-slate-400 ml-1">(Hồi Sức Cấp Cứu)</span>
            </div>
            <span class="font-mono text-[11px] text-brand-600 bg-brand-50 px-2 py-0.5 rounded-lg border border-brand-100">001188023456</span>
          </button>
        </div>
      </div>
    </div>

    <!-- CỘT PHẢI: FORM ĐĂNG NHẬP CHÍNH -->
    <div class="lg:col-span-6">
      <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-6 sm:p-9 login-card border border-blue-100/80">
        
        <!-- Mobile Header (chỉ hiện trên màn hình nhỏ) -->
        <div class="flex items-center gap-3 mb-6 lg:hidden">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-md">
            <i class="ph-bold ph-hospital text-xl"></i>
          </div>
          <div>
            <h2 class="text-lg font-bold text-slate-900 leading-none">CARE IOC</h2>
            <p class="text-[11px] text-brand-600 font-semibold">Trung Tâm Điều Hành Y Tế</p>
          </div>
        </div>

        <div class="mb-6">
          <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Đăng Nhập Cán Bộ</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">Sử dụng số Căn cước công dân gắn chip để truy cập phân hệ IOC.</p>
        </div>

        <!-- FORM ĐĂNG NHẬP -->
        <form id="form-login" action="<?php echo XC_URL; ?>/api/login" method="POST" class="space-y-4" novalidate>
          
          <!-- TRƯỜNG 1: SỐ CCCD -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="login-username" class="block text-xs font-bold text-slate-700">
                Số Căn Cước Công Dân (CCCD) <span class="text-rose-500">*</span>
              </label>
              <span id="cccd-status-badge" class="text-[11px] hidden font-semibold"></span>
            </div>

            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="ph-bold ph-identification-card text-lg"></i>
              </div>
              <input 
                type="text" 
                name="username" 
                id="login-username" 
                required 
                maxlength="12"
                inputmode="numeric"
                pattern="[0-9]{12}"
                placeholder="Nhập 12 chữ số CCCD (VD: 037098045678)"
                class="w-full pl-11 pr-10 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all placeholder:font-sans placeholder:text-slate-400"
                autocomplete="username"
              />
              <div id="cccd-loading-spinner" class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none hidden">
                <i class="ph-bold ph-spinner animate-spin text-brand-600 text-base"></i>
              </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
              <i class="ph ph-info text-xs"></i>
              Nhập chính xác 12 chữ số trên thẻ CCCD hoặc tên tài khoản quản trị.
            </p>
          </div>

          <!-- TRƯỜNG 2: MẬT KHẨU -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="login-password" class="block text-xs font-bold text-slate-700">
                Mật Khẩu Truy Cập <span class="text-rose-500">*</span>
              </label>
              <a href="javascript:void(0)" onclick="handleForgotPassword()" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700 transition">
                Quên mật khẩu?
              </a>
            </div>

            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="ph-bold ph-lock-key text-lg"></i>
              </div>
              <input 
                type="password" 
                name="password" 
                id="login-password" 
                required 
                placeholder="Nhập mật khẩu tài khoản"
                class="w-full pl-11 pr-11 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all placeholder:text-slate-400"
                autocomplete="current-password"
              />
              <button 
                type="button" 
                onclick="togglePasswordVisibility()" 
                tabindex="-1"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition focus:outline-none" 
                title="Hiện/Ẩn mật khẩu"
              >
                <i id="password-toggle-icon" class="ph-bold ph-eye text-base"></i>
              </button>
            </div>
          </div>

          <!-- GHI NHỚ PHIÊN ĐĂNG NHẬP -->
          <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none">
              <input type="checkbox" id="remember-me" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
              <span class="text-xs text-slate-600 font-medium">Ghi nhớ phiên đăng nhập trên thiết bị này</span>
            </label>
          </div>

          <!-- NÚT SUBMIT -->
          <div class="pt-2">
            <button 
              type="submit" 
              id="btn-login"
              class="w-full py-3.5 px-4 bg-gradient-to-r from-brand-600 to-blue-600 hover:from-brand-700 hover:to-blue-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-brand-600/25 active:scale-[0.99] transition-all flex items-center justify-center gap-2"
            >
              <i class="ph-bold ph-sign-in text-lg"></i>
              <span>Xác Thực & Đăng Nhập IOC</span>
            </button>
          </div>
        </form>

        <!-- FOOTER BẢO MẬT & HỖ TRỢ -->
        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
          <div class="flex items-center gap-1.5">
            <i class="ph-bold ph-shield-check text-emerald-500 text-sm"></i>
            <span>Bảo mật SSL 256-bit</span>
          </div>
          <div>
            <span>Hỗ trợ kỹ thuật: <strong class="text-slate-600 font-semibold">024.3825.xxxx</strong></span>
          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- ==================== JAVASCRIPT XỬ LÝ AJAX VÀ ĐỊNH DẠNG JSON ==================== -->
  <script>
    // Điền tài khoản demo nhanh
    function fillAccount(citizenId, password) {
      $('#login-username').val(citizenId).trigger('input');
      $('#login-password').val(password);
      $('#login-username').focus();
    }

    // Toggle hiện/ẩn mật khẩu
    function togglePasswordVisibility() {
      var pwdInput = document.getElementById('login-password');
      var icon = document.getElementById('password-toggle-icon');
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        icon.className = 'ph-bold ph-eye-slash text-base text-brand-600';
      } else {
        pwdInput.type = 'password';
        icon.className = 'ph-bold ph-eye text-base text-slate-400';
      }
    }

    // Thông báo quên mật khẩu
    function handleForgotPassword() {
      Swal.fire({
        icon: 'info',
        title: 'Khôi phục mật khẩu cán bộ',
        text: 'Vui lòng liên hệ Phòng Công nghệ thông tin & Quản trị mạng Bệnh viện để được cấp lại mật khẩu xác thực qua số CCCD.',
        confirmButtonColor: '#0c87eb',
        confirmButtonText: 'Đã hiểu'
      });
    }

    $(document).ready(function() {
      var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      var swalBg = isDark ? '#1a2332' : '#ffffff';
      var swalColor = isDark ? '#ffffff' : '#0f1623';

      // 1. Tự động chuẩn hóa chỉ cho nhập số ở ô CCCD (nếu là chuỗi số)
      $('#login-username').on('input', function() {
        var val = $(this).val();
        // Nếu bắt đầu bằng ký tự số thì chỉ cho phép nhập số
        if (/^\d+$/.test(val) || (val.length > 0 && /^\d/.test(val))) {
          this.value = val.replace(/[^0-9]/g, '');
        }
      });

      // 2. Debounce kiểm tra sự tồn tại của số CCCD (checkExists API)
      var checkTimeout = null;
      $('#login-username').on('keyup change', function() {
        var val = $.trim($(this).val());
        var $badge = $('#cccd-status-badge');
        var $spinner = $('#cccd-loading-spinner');

        clearTimeout(checkTimeout);
        
        // Chỉ kích hoạt kiểm tra khi đủ 12 số CCCD
        if (val.length === 12 && /^\d{12}$/.test(val)) {
          $spinner.removeClass('hidden');
          checkTimeout = setTimeout(function() {
            $.ajax({
              type: 'POST',
              url: '<?php echo XC_URL; ?>/api/checkExists',
              data: { type: 'citizen_id', value: val },
              dataType: 'json',
              success: function(res) {
                $spinner.addClass('hidden');
                if (res.exists) {
                  $badge.removeClass('hidden text-rose-500').addClass('text-emerald-600')
                        .html('<i class="ph-bold ph-check-circle mr-1"></i>Hợp lệ trong hệ thống');
                } else {
                  $badge.removeClass('hidden text-emerald-600').addClass('text-amber-500')
                        .html('<i class="ph-bold ph-warning mr-1"></i>Chưa đăng ký');
                }
              },
              error: function() {
                $spinner.addClass('hidden');
                $badge.addClass('hidden');
              }
            });
          }, 400);
        } else {
          $spinner.addClass('hidden');
          $badge.addClass('hidden');
        }
      });

      // 3. Submit form Đăng Nhập bằng AJAX (Đúng định dạng JSON yêu cầu)
      $('#form-login').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var $form = $(this);

        // Validation phía trình duyệt
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        var username = $.trim($('#login-username').val());
        var password = $('#login-password').val();

        if (!username || !password) {
          Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng nhập đầy đủ Số CCCD và Mật khẩu truy cập!',
            background: swalBg,
            color: swalColor
          });
          return;
        }

        // Hiển thị trạng thái đang xác thực với SweetAlert2
        Swal.fire({
          title: 'Đang xác thực thông tin...',
          text: 'Hệ thống đang đối soát dữ liệu với CSDL cán bộ IOC',
          allowOutsideClick: false,
          background: swalBg,
          color: swalColor,
          didOpen: function() {
            Swal.showLoading();
          }
        });

        // Gửi AJAX POST đến api/login
        $.ajax({
          type: "POST",
          url: $form.attr('action'),
          data: $form.serialize(),
          dataType: 'json',
          success: function(data) {
            // Định dạng response chuẩn: {"status": 200, "message": "...", "redirect": "..."}
            if (data.status === 200) {
              Swal.fire({
                icon: 'success',
                title: 'Đăng nhập thành công!',
                text: data.message || 'Xin chào cán bộ quản trị!',
                timer: 1200,
                showConfirmButton: false,
                background: swalBg,
                color: swalColor
              }).then(function() {
                window.location.href = data.redirect || '<?php echo XC_URL; ?>/admin';
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Đăng nhập thất bại',
                text: data.message || 'Số CCCD hoặc Mật khẩu không chính xác!',
                background: swalBg,
                color: swalColor
              });
            }
          },
          error: function(xhr, status, error) {
            console.error('Login Error:', xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Lỗi máy chủ',
              text: 'Không thể kết nối đến hệ thống máy chủ IOC. Vui lòng thử lại sau!',
              background: swalBg,
              color: swalColor
            });
          }
        });
      });

    });
  </script>
</body>
</html>
