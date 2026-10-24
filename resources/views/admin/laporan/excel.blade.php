{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
{!! '<' . '?mso-application progid="Excel.Sheet"?' . '>' !!}
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Title>Laporan Transaksi Wahana Gedambaan</Title>
  <Subject>Rekapitulasi Tiket Bulanan</Subject>
  <Author>Wahana Gedambaan</Author>
  <Created>{{ now()->toIso8601String() }}</Created>
 </DocumentProperties>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="Calibri" ss:Size="10" ss:Color="#0f172a"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>

  {{-- Header Dokumen --}}
  <Style ss:ID="DocTitle">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#0369a1"/>
  </Style>
  <Style ss:ID="DocSubtitle">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#334155"/>
  </Style>
  <Style ss:ID="DocMeta">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="9" ss:Italic="1" ss:Color="#64748b"/>
  </Style>

  {{-- KPI Summary Box --}}
  <Style ss:ID="KpiLabel">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="8.5" ss:Bold="1" ss:Color="#475569"/>
   <Interior ss:Color="#f8fafc" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="KpiValue">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#0f172a"/>
   <Interior ss:Color="#ffffff" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="KpiValueRevenue">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#0369a1"/>
   <Interior ss:Color="#eff6ff" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>

  {{-- Header Tabel Data --}}
  <Style ss:ID="ColHeader">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#0284c7"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#38bdf8"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#38bdf8"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#0284c7"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="9.5" ss:Bold="1" ss:Color="#ffffff"/>
   <Interior ss:Color="#0284c7" ss:Pattern="Solid"/>
  </Style>

  {{-- Baris Data --}}
  <Style ss:ID="CellCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
  </Style>
  <Style ss:ID="CellKode">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:FontName="Consolas" ss:Size="9.5" ss:Bold="1" ss:Color="#2563eb"/>
   <Interior ss:Color="#eff6ff" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="CellLeft">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
  </Style>
  <Style ss:ID="CellLeftBatal">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Color="#94a3b8" ss:StrikeThrough="1"/>
  </Style>
  <Style ss:ID="CellCurrency">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>
  <Style ss:ID="CellCurrencyBold">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Bold="1"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>
  <Style ss:ID="CellCurrencyBatal">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Color="#94a3b8" ss:StrikeThrough="1"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>

  {{-- Status Badges --}}
  <Style ss:ID="BadgeSelesai">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Bold="1" ss:Color="#065f46"/>
   <Interior ss:Color="#d1fae5" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="BadgePending">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Bold="1" ss:Color="#92400e"/>
   <Interior ss:Color="#fef3c7" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="BadgeBatal">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Bold="1" ss:Color="#991b1b"/>
   <Interior ss:Color="#fee2e2" ss:Pattern="Solid"/>
  </Style>

  {{-- Total Footer --}}
  <Style ss:ID="TotalLabel">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Double" ss:Weight="3" ss:Color="#0f172a"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#0f172a"/>
   </Borders>
   <Font ss:Bold="1" ss:Size="10" ss:Color="#0f172a"/>
   <Interior ss:Color="#f1f5f9" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="TotalValue">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Double" ss:Weight="3" ss:Color="#0f172a"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#0f172a"/>
   </Borders>
   <Font ss:Bold="1" ss:Size="10.5" ss:Color="#0369a1"/>
   <Interior ss:Color="#f1f5f9" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>
 </Styles>

 <Worksheet ss:Name="Laporan {{ $bulanStr }}-{{ $tahun }}">
  <Table ss:DefaultRowHeight="18">
   {{-- Definisi Lebar Tiap Kolom --}}
   <Column ss:Width="36"/>  {{-- 1: No --}}
   <Column ss:Width="115"/> {{-- 2: Kode Tiket --}}
   <Column ss:Width="110"/> {{-- 3: Waktu Pesan --}}
   <Column ss:Width="140"/> {{-- 4: Nama Pembeli --}}
   <Column ss:Width="105"/> {{-- 5: No HP --}}
   <Column ss:Width="120"/> {{-- 6: Alamat --}}
   <Column ss:Width="120"/> {{-- 7: Speedboat --}}
   <Column ss:Width="85"/>  {{-- 8: Tipe Hari --}}
   <Column ss:Width="85"/>  {{-- 9: Tarif --}}
   <Column ss:Width="80"/>  {{-- 10: Diskon --}}
   <Column ss:Width="100"/> {{-- 11: Total Bayar --}}
   <Column ss:Width="115"/> {{-- 12: Status --}}
   <Column ss:Width="120"/> {{-- 13: Waktu Scan/Boarding --}}

   {{-- Judul Laporan --}}
   <Row ss:Height="24">
    <Cell ss:MergeAcross="12" ss:StyleID="DocTitle">
     <Data ss:Type="String">LAPORAN TRANSAKSI WAHANA GEDAMBAAN</Data>
    </Cell>
   </Row>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="12" ss:StyleID="DocSubtitle">
     <Data ss:Type="String">Periode: {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }} {{ $filterDesc ? '• '.$filterDesc : '' }}</Data>
    </Cell>
   </Row>
   <Row ss:Height="16">
    <Cell ss:MergeAcross="12" ss:StyleID="DocMeta">
     <Data ss:Type="String">Waktu Unduh: {{ now()->format('d/m/Y H:i:s') }} WITA | Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }}</Data>
    </Cell>
   </Row>
   <Row ss:Height="8"></Row>

   {{-- KPI Summary Box (2 Baris) --}}
   <Row ss:Height="18">
    <Cell ss:MergeAcross="2" ss:StyleID="KpiLabel"><Data ss:Type="String">TOTAL PENDAPATAN BERSIH</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">TOTAL TIKET</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">SUDAH DIGUNAKAN</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">BELUM DIGUNAKAN</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">DIBATALKAN (VOID)</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">TOTAL DISKON</Data></Cell>
   </Row>
   <Row ss:Height="22">
    <Cell ss:MergeAcross="2" ss:StyleID="KpiValueRevenue"><Data ss:Type="Number">{{ (int) $totalPendapatan }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValue"><Data ss:Type="Number">{{ $totalTransaksi }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValue"><Data ss:Type="Number">{{ $tiketSelesai }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValue"><Data ss:Type="Number">{{ $tiketPending }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValue"><Data ss:Type="Number">{{ $tiketBatal }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValueRevenue"><Data ss:Type="Number">{{ (int) $totalDiskon }}</Data></Cell>
   </Row>
   <Row ss:Height="12"></Row>

   {{-- Header Tabel Data --}}
   <Row ss:Height="24">
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">No</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Kode Tiket</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Waktu Pesan</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Nama Pembeli</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">No. HP</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Alamat</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Speedboat</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Tipe Hari</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Tarif</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Diskon</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Total Bayar</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Status</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Waktu Scan / Boarding</Data></Cell>
   </Row>

   {{-- Baris Data Transaksi --}}
   @forelse ($tikets as $index => $tiket)
    @php
        $isBatal = $tiket->status === \App\Models\Tiket::STATUS_BATAL;
        $isSelesai = $tiket->status === \App\Models\Tiket::STATUS_SELESAI;
    @endphp
    <Row ss:Height="20">
     <Cell ss:StyleID="CellCenter"><Data ss:Type="Number">{{ $index + 1 }}</Data></Cell>
     <Cell ss:StyleID="CellKode"><Data ss:Type="String">{{ $tiket->kode_tiket }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $tiket->tanggal_pemesanan ? $tiket->tanggal_pemesanan->format('d/m/Y H:i') : '-' }}</Data></Cell>
     <Cell ss:StyleID="{{ $isBatal ? 'CellLeftBatal' : 'CellLeft' }}"><Data ss:Type="String">{{ htmlspecialchars($tiket->nama_pembeli, ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $tiket->no_hp }}</Data></Cell>
     <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ htmlspecialchars($tiket->alamat ?? '-', ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ htmlspecialchars($tiket->kapal ? $tiket->kapal->nama_kapal : '-', ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $tiket->is_weekend ? 'Weekend' : 'Hari Biasa' }}</Data></Cell>
     <Cell ss:StyleID="CellCurrency"><Data ss:Type="Number">{{ (int) $tiket->tarif }}</Data></Cell>
     <Cell ss:StyleID="CellCurrency"><Data ss:Type="Number">{{ (int) $tiket->diskon }}</Data></Cell>
     <Cell ss:StyleID="{{ $isBatal ? 'CellCurrencyBatal' : 'CellCurrencyBold' }}"><Data ss:Type="Number">{{ (int) $tiket->total_bayar }}</Data></Cell>
     <Cell ss:StyleID="{{ $isBatal ? 'BadgeBatal' : ($isSelesai ? 'BadgeSelesai' : 'BadgePending') }}">
      <Data ss:Type="String">{{ $isBatal ? 'Dibatalkan' : ($isSelesai ? 'Sudah Digunakan' : 'Belum Digunakan') }}</Data>
     </Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $tiket->tanggal_scan ? $tiket->tanggal_scan->format('d/m/Y H:i') : '-' }}</Data></Cell>
    </Row>
   @empty
    <Row ss:Height="24">
     <Cell ss:MergeAcross="12" ss:StyleID="CellCenter"><Data ss:Type="String">Tidak ada data transaksi pada periode ini.</Data></Cell>
    </Row>
   @endforelse

   {{-- Baris Grand Total di Bawah --}}
   <Row ss:Height="24">
    <Cell ss:MergeAcross="9" ss:StyleID="TotalLabel"><Data ss:Type="String">TOTAL PENDAPATAN BERSIH :</Data></Cell>
    <Cell ss:StyleID="TotalValue"><Data ss:Type="Number">{{ (int) $totalPendapatan }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="TotalLabel"></Cell>
   </Row>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.5" x:Left="0.5" x:Right="0.5" x:Top="0.5"/>
   </PageSetup>
   <FitToPage/>
   <Print>
    <FitWidth>1</FitWidth>
    <FitHeight>0</FitHeight>
    <ValidPrinterInfo/>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
