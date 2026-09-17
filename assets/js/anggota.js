const KUNCI_ANGGOTA = "simpus_anggota_tambahan";

async function muatDaftarAnggota() {
    const response = await fetch("../data/anggota.json");
    if (!response.ok) {
        throw new Error("Data anggota tidak dapat dimuat.");
    }

    const anggotaJson = await response.json();
    const anggotaTambahan = JSON.parse(localStorage.getItem(KUNCI_ANGGOTA) || "[]");
    return [
        ...anggotaJson.map(function (item) {
            return { ...item, sumber: "json" };
        }),
        ...anggotaTambahan.map(function (item, index) {
            return { ...item, sumber: "local", localIndex: index };
        })
    ];
}

function escapeHtml(nilai) {
    return String(nilai ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function formatTanggal(tanggal) {
    if (!tanggal) return "-";
    return new Date(tanggal).toLocaleDateString("id-ID");
}

function tampilkanDaftarAnggota(anggota) {
    const tbody = document.querySelector("#daftar-anggota");
    if (!tbody) return;

    tbody.innerHTML = anggota.map(function (item) {
        const dataLokal = item.sumber === "local" ? `data-local-index="${item.localIndex}"` : "";
        return `
            <tr ${dataLokal}>
                <td>${escapeHtml(item.id)}</td>
                <td>${escapeHtml(item.nama)}</td>
                <td>${escapeHtml(item.alamat || "-")}</td>
                <td>${escapeHtml(item.no_hp || "-")}</td>
                <td>${formatTanggal(item.tanggal_bergabung)}</td>
                <td>
                    <button type="button" class="btn-icon btn-edit" ${dataLokal} aria-label="Edit ${escapeHtml(item.nama)}">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button type="button" class="btn-icon btn-delete" ${dataLokal} aria-label="Hapus ${escapeHtml(item.nama)}">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>`;
    }).join("");
}

function simpanAnggotaBaru(anggotaBaru) {
    const anggotaTambahan = JSON.parse(localStorage.getItem(KUNCI_ANGGOTA) || "[]");
    anggotaTambahan.push(anggotaBaru);
    localStorage.setItem(KUNCI_ANGGOTA, JSON.stringify(anggotaTambahan));
}

function bukaEditAnggota(index) {
    window.location.href = `tambah.html?edit=${index}`;
}

function hapusAnggotaTambahan(index) {
    const anggotaTambahan = JSON.parse(localStorage.getItem(KUNCI_ANGGOTA) || "[]");
    if (index >= 0 && index < anggotaTambahan.length) {
        anggotaTambahan.splice(index, 1);
        localStorage.setItem(KUNCI_ANGGOTA, JSON.stringify(anggotaTambahan));
    }
}

function initDaftarAnggota() {
    const tbody = document.querySelector("#daftar-anggota");
    if (!tbody) return;

    muatDaftarAnggota()
        .then(tampilkanDaftarAnggota)
        .catch(function (error) {
            tbody.innerHTML = `<tr><td colspan="6">${error.message}</td></tr>`;
        });

    tbody.addEventListener("click", function (event) {
        const tombolEdit = event.target.closest(".btn-edit");
        const tombolHapus = event.target.closest(".btn-delete");
        const tombol = tombolEdit || tombolHapus;
        if (!tombol) return;

        const index = Number(tombol.dataset.localIndex);
        if (!Number.isInteger(index)) {
            alert(`Anggota dari data JSON tidak dapat ${tombolEdit ? "diedit" : "dihapus"} permanen dari halaman ini.`);
            return;
        }

        const anggotaTambahan = JSON.parse(localStorage.getItem(KUNCI_ANGGOTA) || "[]");
        const anggota = anggotaTambahan[index];
        if (!anggota) return;

        if (tombolEdit) {
            bukaEditAnggota(index);
            return;
        }

        if (confirm(`Hapus anggota "${anggota.nama}"?`)) {
            hapusAnggotaTambahan(index);
            muatDaftarAnggota().then(tampilkanDaftarAnggota);
        }
    });
}

function initFormAnggota() {
    const form = document.querySelector("#form-tambah");
    if (!form) return;

    const parameterEdit = new URLSearchParams(window.location.search).get("edit");
    const indexEdit = parameterEdit === null ? -1 : Number(parameterEdit);
    const anggotaTambahan = JSON.parse(localStorage.getItem(KUNCI_ANGGOTA) || "[]");
    const anggotaYangDiedit = anggotaTambahan[indexEdit];

    if (anggotaYangDiedit) {
        form.querySelector("[name='nama']").value = anggotaYangDiedit.nama || "";
        form.querySelector("[name='no_anggota']").value = anggotaYangDiedit.id || "";
        form.querySelector("[name='alamat']").value = anggotaYangDiedit.alamat || "";
        form.querySelector("[name='no_hp']").value = anggotaYangDiedit.no_hp || "";
        const judulForm = document.querySelector("h2");
        if (judulForm) judulForm.textContent = "Edit Anggota";
        form.querySelector("button[type='submit']").textContent = "Simpan Perubahan";
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        const data = new FormData(form);
        const anggotaBaru = {
            id: data.get("no_anggota").trim(),
            nama: data.get("nama").trim(),
            alamat: data.get("alamat").trim(),
            no_hp: data.get("no_hp").trim(),
            tanggal_bergabung: new Date().toISOString()
        };

        if (anggotaYangDiedit) {
            anggotaTambahan[indexEdit] = anggotaBaru;
            localStorage.setItem(KUNCI_ANGGOTA, JSON.stringify(anggotaTambahan));
            alert("Data anggota berhasil diperbarui.");
        } else {
            simpanAnggotaBaru(anggotaBaru);
            alert("Data anggota berhasil disimpan.");
        }
        window.location.href = "list.html";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initDaftarAnggota();
    initFormAnggota();
});
