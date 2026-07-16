@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Ödüller</span>
            <a href="{{ route('cms.awards.create') }}" class="btn btn-sm btn-primary">Yeni Ödül Ekle</a>
        </div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sıralama</th>
                    <th>Resim</th>
                    <th>Ödül Adı</th>
                    <th>Ödül Tarihi</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($awards as $item)
                    <tr>
                        <th>{{ $item->hit }}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Odüller" data-src="{{ $item->image() }}"
                                        data-caption="{{ $item->name }}">
                                    <img src="{{ $item->image() }}" width="35" height="35" alt="{{ $item->name }}">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>{{ $item->name }}</th>
                        <th>{{ $item->prize_date?->format('d.m.Y') ?? '-' }}</th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{ route('cms.awards.publish', $item->id) }}')"
                                       @if($item->published) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th class="islemler">
                            <a href="{{ route('cms.awards.edit', $item->id) }}" class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{ route('cms.awards.destroy', $item->id) }}')"
                               class="btn bg-error" title="Sil">
                                <i class="las la-trash"></i>
                            </a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section("extraJs")
    <script>
        function activate(route) {
            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
        }

        function deleteFunc(route) {
            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
        }
    </script>
@endsection
