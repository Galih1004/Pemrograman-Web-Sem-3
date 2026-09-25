const KUNCI_BUKU = "simpus_buku_tambahan";

async function muatDaftarBuku() {
	const response = await fetch("../data/buku.json");
	if (!response.ok) {
		throw new Error("Data buku tidak dapat dimuat.");
	}

	const bukuJson = await response.json();
	const bukuTambahan = JSON.parse(localStorage.getItem(KUNCI_BUKU) || "[]");
	return [
		...bukuJson.map(function (item) {
			return { ...item, sumber: "json" };
		}),
		...bukuTambahan.map(function (item, index) {
			return { ...item, sumber: "local", localIndex: index };
		})
	];
}

function tampilkanStok(stok) {
	const jumlah = Number(stok) || 0;
	const status = jumlah > 0 ? "Tersedia" : "Habis";
	const kelas = jumlah > 0 ? "badge-tersedia" : "badge-habis";
	return `<span class="badge-stok ${kelas}">${jumlah} ${status}</span>`;
}

function escapeHtml(nilai) {
	return String(nilai ?? "")
		.replaceAll("&", "&amp;")
		.replaceAll("<", "&lt;")
		.replaceAll(">", "&gt;")
		.replaceAll('"', "&quot;")
		.replaceAll("'", "&#039;");
}

function tampilkanDaftarBuku(buku) {
	const tbody = document.querySelector("#daftar-buku");
	if (!tbody) return;

	tbody.innerHTML = buku.map(function (item) {
		const dataTambahan = item.sumber === "local" ? `data-local-index="${item.localIndex}"` : "";
		return `
			<tr ${dataTambahan}>
				<td>${escapeHtml(item.judul)}</td>
				<td>${escapeHtml(item.pengarang)}</td>
				<td>${escapeHtml(item.tahun)}</td>
				<td>${tampilkanStok(item.stok)}</td>
				<td class="aksi-petugas">
					<button type="button" class="btn-icon btn-edit" ${item.sumber === "local" ? `data-local-index="${item.localIndex}"` : ""} aria-label="Edit ${escapeHtml(item.judul)}">
						<i class="bi bi-pencil-fill"></i>
					</button>
					<button type="button" class="btn-icon btn-delete" ${item.sumber === "local" ? `data-local-index="${item.localIndex}"` : ""} aria-label="Hapus ${escapeHtml(item.judul)}">
						<i class="bi bi-trash-fill"></i>
					</button>
				</td>
			</tr>`;
	}).join("");
}

function simpanBukuBaru(bukuBaru) {
	const bukuTambahan = JSON.parse(localStorage.getItem(KUNCI_BUKU) || "[]");
	bukuTambahan.push(bukuBaru);
	localStorage.setItem(KUNCI_BUKU, JSON.stringify(bukuTambahan));
}

function hapusBukuTambahan(index) {
	const bukuTambahan = JSON.parse(localStorage.getItem(KUNCI_BUKU) || "[]");
	if (index >= 0 && index < bukuTambahan.length) {
		bukuTambahan.splice(index, 1);
		localStorage.setItem(KUNCI_BUKU, JSON.stringify(bukuTambahan));
	}
}

function editBukuTambahan(index) {
	window.location.href = `tambah.php?edit=${index}`;
}

function initDaftarBuku() {
	const tbody = document.querySelector("#daftar-buku");
	if (!tbody) return;

	muatDaftarBuku()
		.then(tampilkanDaftarBuku)
		.catch(function (error) {
			tbody.innerHTML = `<tr><td colspan="5">${error.message}</td></tr>`;
		});

	tbody.addEventListener("click", function (event) {
		const tombolEdit = event.target.closest(".btn-edit");
		if (tombolEdit) {
			const index = Number(tombolEdit.dataset.localIndex);
			if (!Number.isInteger(index)) {
				alert("Buku dari data JSON tidak dapat diedit permanen dari halaman ini.");
				return;
			}
			editBukuTambahan(index);
			return;
		}

		const tombolHapus = event.target.closest(".btn-delete");
		if (!tombolHapus) return;

		const index = Number(tombolHapus.dataset.localIndex);
		if (!Number.isInteger(index)) {
			alert("Buku dari data JSON tidak dapat dihapus permanen dari halaman ini.");
			return;
		}
		const bukuTambahan = JSON.parse(localStorage.getItem(KUNCI_BUKU) || "[]");
		const buku = bukuTambahan[index];
		if (!buku || !confirm(`Hapus buku "${buku.judul}"?`)) return;

		hapusBukuTambahan(index);
		muatDaftarBuku().then(tampilkanDaftarBuku);
	});
}

function initFormBuku() {
	const form = document.querySelector("#form-tambah");
	if (!form) return;

	const parameterEdit = new URLSearchParams(window.location.search).get("edit");
	const indexEdit = parameterEdit === null ? -1 : Number(parameterEdit);
	const bukuTambahan = JSON.parse(localStorage.getItem(KUNCI_BUKU) || "[]");
	const bukuYangDiedit = bukuTambahan[indexEdit];

	if (bukuYangDiedit) {
		form.querySelector("[name='judul']").value = bukuYangDiedit.judul || "";
		form.querySelector("[name='pengarang']").value = bukuYangDiedit.pengarang || "";
		form.querySelector("[name='tahun']").value = bukuYangDiedit.tahun || "";
		form.querySelector("[name='isbn']").value = bukuYangDiedit.isbn || "";
		form.querySelector("[name='stok']").value = bukuYangDiedit.stok || 0;
		form.querySelector("[name='kategori']").value = bukuYangDiedit.kategori || "fiksi";
		const judulForm = document.querySelector("h2");
		if (judulForm) judulForm.textContent = "Edit Buku";
		form.querySelector("button[type='submit']").textContent = "Simpan Perubahan";
	}

	form.addEventListener("submit", function (event) {
		event.preventDefault();
		const data = new FormData(form);
		const bukuBaru = {
			judul: data.get("judul").trim(),
			pengarang: data.get("pengarang").trim(),
			tahun: Number(data.get("tahun")),
			isbn: data.get("isbn").trim(),
			stok: Number(data.get("stok")),
			kategori: data.get("kategori")
		};

		if (bukuYangDiedit) {
			bukuTambahan[indexEdit] = bukuBaru;
			localStorage.setItem(KUNCI_BUKU, JSON.stringify(bukuTambahan));
			alert("Data buku berhasil diperbarui.");
		} else {
			simpanBukuBaru(bukuBaru);
			alert("Data buku berhasil disimpan.");
		}
		window.location.href = "list.php";
	});
}

document.addEventListener("DOMContentLoaded", function () {
	initDaftarBuku();
	initFormBuku();
});