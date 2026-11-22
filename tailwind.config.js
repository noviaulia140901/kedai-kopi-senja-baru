// Mengambil konfigurasi font bawaan Tailwind CSS.
import defaultTheme from 'tailwindcss/defaultTheme';

// Mengambil plugin Tailwind untuk mempercantik elemen formulir.
import forms from '@tailwindcss/forms';

/** Konfigurasi utama Tailwind CSS */
export default {
    // Menentukan file yang akan diperiksa Tailwind
    // untuk menemukan class CSS yang digunakan.
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Mengatur tema tampilan aplikasi.
    theme: {
        // extend digunakan untuk menambahkan pengaturan
        // tanpa menghapus pengaturan bawaan Tailwind.
        extend: {

            // Mengatur jenis huruf utama aplikasi.
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            // Palet warna khusus Kedai Kopi Senja.
            colors: {
                senja: {

                    // Hijau zaitun untuk warna pendukung.
                    olive: '#5A5E27',

                    // Merah burgundy untuk tombol utama.
                    burgundy: '#640017',

                    // Cokelat gelap untuk teks dan navigasi.
                    brown: '#2F1B1A',

                    // Cream untuk latar belakang terang.
                    cream: '#EFEFC9',
                },
            },
        },
    },

    // Mengaktifkan plugin untuk elemen formulir.
    plugins: [forms],
};
