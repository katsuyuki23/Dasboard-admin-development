<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('anak') ? $this->route('anak')->id_anak : null;

        return [
            'nama' => ['required', 'string', 'max:100'],
            'nomor_induk' => ['nullable', 'string', 'max:50', Rule::unique('anak', 'nomor_induk')->ignore($id, 'id_anak')],
            'nik' => ['nullable', 'string', 'max:20', Rule::unique('anak', 'nik')->ignore($id, 'id_anak')],
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('anak', 'nisn')->ignore($id, 'id_anak')],
            'tempat_lahir' => ['nullable', 'string', 'max:50'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'status_anak' => ['required', 'in:YATIM,PIATU,YATIM_PIATU'],
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'nama_ibu' => ['nullable', 'string', 'max:100'],
            'nama_wali' => ['nullable', 'string', 'max:100'],
            'hubungan_wali' => ['nullable', 'string', 'max:50'],
            'no_hp_wali' => ['nullable', 'string', 'max:20'],
            'no_hp_keluarga' => ['nullable', 'string', 'max:20'],
            'alamat_wali' => ['nullable', 'string'],
            'alamat_asal' => ['nullable', 'string'],
            'alasan_masuk' => ['nullable', 'string'],
            'tanggal_masuk' => ['nullable', 'date'],
            'tanggal_keluar' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
