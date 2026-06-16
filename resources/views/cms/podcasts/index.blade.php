@extends("cms.partial.layout")

@section("content")
    <div class="card">
        <div class="card-header">
            Podcastler

            @if(request('lang_id') && request('lang_id') != 1)
                @php
                    $currentLang = $languages->where('id', request('lang_id'))->first();
                @endphp
                (Dil: {{ strtoupper($currentLang->code ?? '') }})
            @endif
        </div>

        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">
                <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Link</th>
                    <th>Yayın Tarihi</th>
                    <th>Sayfa</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($podcasts as $item)
                    <tr>
                        <td>{{ $item->name }}</td>

                        <td>
                            <a href="{{ $item->link }}" target="_blank">
                                {{ Str::limit($item->link, 50) }}
                            </a>
                        </td>

                        <td>
                            {{ $item->published ?? '-' }}
                        </td>

                        <td>
                            {{ $item->page->title ?? 'Sayfa Yok' }}
                        </td>

                        <td class="islemler">
                            <a onclick="deleteFunc('{{ route("cms.podcast.destroy", $item->id) }}')"
                               class="btn bg-error">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection


@section("extraJs")
    <script>
        function deleteFunc(route) {
            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(() => {
                    notyf.error("İşlem başarısız");
                });
        }
    </script>
@endsection
