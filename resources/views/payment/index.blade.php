<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pembayaran Booking Futsal</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Midtrans Sandbox -->
  <script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
  </script>
  <!-- Production: src="https://app.midtrans.com/snap/snap.js" -->
</head>
<body class="bg-gray-50">
  <div class="min-h-screen py-10 px-4">
    <div class="max-w-2xl mx-auto">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Booking</h1>
        <p class="text-gray-600">Silakan review informasi booking Anda sebelum melakukan pembayaran</p>
      </div>

      <!-- Booking Details -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Booking</h2>
        
        <div class="space-y-4">
          <!-- Nama Lapangan -->
          <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
            <p class="text-lg font-semibold text-gray-900">{{ $bookingData['lapangan'] ?? '-' }}</p>
          </div>

          <!-- Tanggal -->
          <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <p class="text-lg font-semibold text-gray-900">
              @if(!empty($bookingData['tanggal']))
                {{ \Carbon\Carbon::parse($bookingData['tanggal'])->format('l, d F Y') }}
              @else
                -
              @endif
            </p>
          </div>

          <!-- Waktu -->
          <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Booking</label>
            <p class="text-lg font-semibold text-gray-900">
              {{ $bookingData['jam_mulai'] ?? '-' }} - {{ $bookingData['jam_selesai'] ?? '-' }}
              <span class="text-sm text-gray-600 ml-2">({{ $bookingData['durasi'] ?? 0 }} jam)</span>
            </p>
          </div>

          <!-- Nama Pemesan -->
          <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
            <p class="text-lg font-semibold text-gray-900">{{ $bookingData['nama'] ?? '-' }}</p>
          </div>

          <!-- Region -->
          <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
            <p class="text-lg font-semibold text-gray-900 capitalize">{{ $bookingData['region'] ?? '-' }}</p>
          </div>

          <!-- Catatan (jika ada) -->
          @if(!empty($bookingData['catatan']))
            <div class="border-b pb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
              <p class="text-gray-900">{{ $bookingData['catatan'] }}</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Price Summary -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Harga</h2>
        
        <div class="space-y-3">
          <div class="flex justify-between items-center pb-3 border-b">
            <span class="text-gray-700">Harga per jam</span>
            <span class="font-semibold text-gray-900">Rp {{ number_format($bookingData['harga_per_jam'] ?? 0, 0, ',', '.') }}</span>
          </div>
          
          <div class="flex justify-between items-center pb-3 border-b">
            <span class="text-gray-700">Durasi</span>
            <span class="font-semibold text-gray-900">{{ $bookingData['durasi'] ?? 0 }} jam</span>
          </div>

          <div class="flex justify-between items-center pt-3">
            <span class="text-lg font-semibold text-gray-900">Total</span>
            <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($bookingData['total_harga'] ?? 0, 0, ',', '.') }}</span>
          </div>
        </div>
      </div>

      <!-- Payment Status Message -->
      @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-md mb-6">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-md mb-6">
          @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <!-- Action Buttons -->
      <div class="flex gap-4">
        <a href="{{ route('boking.form') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-3 px-4 rounded-lg text-center transition">
          Kembali
        </a>
        <button id="pay-button" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
          Lanjut ke Pembayaran
        </button>
      </div>
    </div>
  </div>

  <script>
    // Gunakan order_id dari server (session) untuk menghindari duplikasi
    const orderId = '{{ session("order_id") ?? "" }}';
    const bookingDataRaw = {!! json_encode($bookingData ?? []) !!};
    
    // Validasi orderId exist
    if (!orderId) {
      console.error('Order ID tidak ditemukan di session');
      alert('Data pemesanan tidak valid. Silakan lakukan pemesanan ulang.');
    }
    
    document.getElementById('pay-button').addEventListener('click', async () => {
      try {
        const payButton = document.getElementById('pay-button');
        payButton.disabled = true;
        payButton.textContent = 'Sedang memproses...';
        
        console.log('Order ID:', orderId);
        console.log('Booking Data:', bookingDataRaw);
        
        // Validasi booking data
        if (!bookingDataRaw || Object.keys(bookingDataRaw).length === 0) {
          alert('Data booking tidak ditemukan. Silakan ulangi pemesanan.');
          payButton.disabled = false;
          payButton.textContent = 'Lanjut ke Pembayaran';
          return;
        }

        // Validasi order ID
        if (!orderId) {
          alert('Order ID tidak ditemukan. Silakan ulangi pemesanan.');
          payButton.disabled = false;
          payButton.textContent = 'Lanjut ke Pembayaran';
          return;
        }

        const totalHarga = parseInt('{{ intval($bookingData["total_harga"] ?? 0) }}') || 0;
        
        if (totalHarga === 0) {
          alert('Total harga tidak valid. Silakan periksa kembali booking Anda.');
          payButton.disabled = false;
          payButton.textContent = 'Lanjut ke Pembayaran';
          return;
        }

        console.log('Total Harga:', totalHarga);

        // Request token dari server
        const res = await fetch("/api/payment-token", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            order_id: orderId,
            gross_amount: totalHarga,
            booking_data: bookingDataRaw
          })
        });

        console.log('Payment API Response Status:', res.status);

        if (!res.ok) {
          const errorData = await res.json().catch(() => ({}));
          console.error('API Error Response:', errorData);
          throw new Error(`HTTP error! status: ${res.status} - ${errorData.error || 'Unknown error'}`);
        }

        const data = await res.json();
        console.log('Payment Token Response:', data);

        if (!data.token) {
          console.error('API Response Error:', data);
          alert('Error: ' + (data.error || 'Gagal membuat token pembayaran. Silakan coba lagi.'));
          payButton.disabled = false;
          payButton.textContent = 'Lanjut ke Pembayaran';
          return;
        }

        // Buka Midtrans Snap
        window.snap.pay(data.token, {
          onSuccess: function(result) {
            console.log('Pembayaran sukses:', result);
            
            // Auto-trigger webhook untuk update database
            console.log('Triggering webhook for order_id:', orderId);
            fetch('/midtrans/test-webhook?order_id=' + orderId, {
              method: 'GET',
              headers: {
                'Content-Type': 'application/json',
              }
            })
            .then(res => res.json())
            .then(data => {
              console.log('Webhook triggered:', data);
              // Redirect ke success page setelah webhook triggered
              window.location.href = '/payment/success?order_id=' + orderId;
            })
            .catch(err => {
              console.error('Webhook error:', err);
              // Tetap redirect meski webhook error
              window.location.href = '/payment/success?order_id=' + orderId;
            });
          },
          onPending: function(result) {
            console.log('Pembayaran pending:', result);
            alert('Pembayaran sedang diproses. Silakan tunggu...');
            payButton.disabled = false;
            payButton.textContent = 'Lanjut ke Pembayaran';
          },
          onError: function(result) {
            console.log('Pembayaran gagal:', result);
            alert('Pembayaran gagal. Error: ' + (result.status_message || 'Silakan coba lagi.'));
            payButton.disabled = false;
            payButton.textContent = 'Lanjut ke Pembayaran';
          },
          onClose: function() {
            console.log('Pop-up pembayaran ditutup');
            payButton.disabled = false;
            payButton.textContent = 'Lanjut ke Pembayaran';
          }
        });
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
        const payButton = document.getElementById('pay-button');
        payButton.disabled = false;
        payButton.textContent = 'Lanjut ke Pembayaran';
      }
    });
  </script>
</body>
</html>
