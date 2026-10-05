---
name: blade-form-frontend-builder
description: "Petunjuk standar dan lengkap untuk pembuatan form, komponen UI, serta penanganan validasi pada Laravel Blade dan Tailwind CSS."
---

# Frontend and Blade Component Guidelines

Saat membuat atau merombak halaman, form, serta komponen UI di Laravel Blade:

---

## 1. Standar Form Input (Laravel Blade & Tailwind CSS)
* **Keamanan:** Wajib menyertakan directive `@csrf` di bagian atas dalam tag `<form>`.
* **Method Spoofing:** Gunakan `@method('PUT')`, `@method('PATCH')`, atau `@method('DELETE')` untuk method non-POST.
* **Retensi Old Value:** Gunakan `value="{{ old('field_name', $data->field_name ?? '') }}"` pada setiap field input agar data tidak hilang saat validasi gagal.
* **Error Handling:** Tampilkan indikator border merah dan pesan error di bawah input jika terjadi validasi gagal.
* **Layout:** Bungkus setiap input dalam kontainer `mb-4` atau `mb-5` dengan label yang jelas.

### Contoh Implementasi Form Lengkap:
```html
<form action="{{ route('users.store') }}" method="POST" class="space-y-5">
    @csrf

    <!-- Input Text Standard -->
    <div class="mb-4">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name', $user->name ?? '') }}" 
               class="w-full px-4 py-2.5 text-sm text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200 @error('name') border-red-500 focus:ring-red-200 dark:focus:ring-red-900 @else border-gray-300 focus:border-blue-500 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white @enderror"
               placeholder="Masukkan nama lengkap" 
               required>
        @error('name')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- Select / Dropdown -->
    <div class="mb-4">
        <label for="role" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">
            Kategori / Peran <span class="text-red-500">*</span>
        </label>
        <select id="role" 
                name="role" 
                class="w-full px-4 py-2.5 text-sm text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200 @error('role') border-red-500 focus:ring-red-200 @else border-gray-300 focus:border-blue-500 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white @enderror">
            <option value="">-- Pilih Peran --</option>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
        </select>
        @error('role')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tombol Submit & Batal -->
    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors duration-200 dark:bg-blue-500 dark:hover:bg-blue-600">
            Simpan Data
        </button>
        <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-100 transition-colors duration-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            Batal
        </a>
    </div>
</form>