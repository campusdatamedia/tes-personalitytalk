
			<!-- Sidebar -->
			<div class="list-group" style="width: 15rem;">
				<a href="/admin" class="list-group-item list-group-item-action {{ Request::path() == 'admin' ? 'active' : '' }}">Ringkasan</a>
				<a href="/admin/tes" class="list-group-item list-group-item-action {{ is_int(strpos(Request::path(), 'admin/tes')) ? 'active' : '' }}">Tes</a>
				<a href="/admin/paket-soal" class="list-group-item list-group-item-action {{ is_int(strpos(Request::path(), 'admin/paket-soal')) ? 'active' : '' }}">Paket Soal</a>
				<a href="/admin/hasil" class="list-group-item list-group-item-action {{ is_int(strpos(Request::path(), 'admin/hasil')) ? 'active' : '' }}">Hasil</a>
			</div>