{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
{!! '<' . '?mso-application progid="Excel.Sheet"?' . '>' !!}
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Title>Laporan Catatan Pengeluaran Wahana Gedambaan</Title>
  <Subject>Rekapitulasi Pengeluaran Operasional</Subject>
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

  <Style ss:ID="DocTitle">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#be123c"/>
  </Style>
  <Style ss:ID="DocSubtitle">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#334155"/>
  </Style>
  <Style ss:ID="DocMeta">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="9" ss:Italic="1" ss:Color="#64748b"/>
  </Style>

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
  <Style ss:ID="KpiValueExpense">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#cbd5e1"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#be123c"/>
   <Interior ss:Color="#fff1f2" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>

  <Style ss:ID="ColHeader">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#be123c"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#fb7185"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#fb7185"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#be123c"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="9.5" ss:Bold="1" ss:Color="#ffffff"/>
   <Interior ss:Color="#e11d48" ss:Pattern="Solid"/>
  </Style>

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
   <Font ss:FontName="Consolas" ss:Size="9.5" ss:Bold="1" ss:Color="#be123c"/>
   <Interior ss:Color="#fff1f2" ss:Pattern="Solid"/>
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
  <Style ss:ID="CellCurrencyBold">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>
   </Borders>
   <Font ss:Bold="1" ss:Color="#be123c"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>

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
   <Font ss:Bold="1" ss:Size="10.5" ss:Color="#be123c"/>
   <Interior ss:Color="#fff1f2" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="&quot;Rp &quot;#,##0"/>
  </Style>
 </Styles>

 <Worksheet ss:Name="Pengeluaran {{ $bulanStr }}-{{ $tahun }}">
  <Table ss:DefaultRowHeight="18">
   <Column ss:Width="38"/>  {{-- 1: No --}}
   <Column ss:Width="120"/> {{-- 2: Kode Pengeluaran --}}
   <Column ss:Width="95"/>  {{-- 3: Tanggal --}}
   <Column ss:Width="190"/> {{-- 4: Nama Pengeluaran --}}
   <Column ss:Width="220"/> {{-- 5: Keterangan --}}
   <Column ss:Width="115"/> {{-- 6: Dicatat Oleh --}}
   <Column ss:Width="120"/> {{-- 7: Nominal --}}

   <Row ss:Height="24">
    <Cell ss:MergeAcross="6" ss:StyleID="DocTitle">
     <Data ss:Type="String">LAPORAN CATATAN PENGELUARAN WAHANA GEDAMBAAN</Data>
    </Cell>
   </Row>
   <Row ss:Height="18">
    <Cell ss:MergeAcross="6" ss:StyleID="DocSubtitle">
     <Data ss:Type="String">Periode: {{ $periodeLabel }}</Data>
    </Cell>
   </Row>
   <Row ss:Height="16">
    <Cell ss:MergeAcross="6" ss:StyleID="DocMeta">
     <Data ss:Type="String">Waktu Unduh: {{ now()->format('d/m/Y H:i:s') }} WIB | Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }}</Data>
    </Cell>
   </Row>
   <Row ss:Height="8"></Row>

   {{-- KPI Summary Box --}}
   <Row ss:Height="18">
    <Cell ss:MergeAcross="2" ss:StyleID="KpiLabel"><Data ss:Type="String">TOTAL PENGELUARAN</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">JUMLAH CATATAN</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiLabel"><Data ss:Type="String">PENGELUARAN TERBESAR</Data></Cell>
   </Row>
   <Row ss:Height="22">
    <Cell ss:MergeAcross="2" ss:StyleID="KpiValueExpense"><Data ss:Type="Number">{{ (int) $totalPengeluaran }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValue"><Data ss:Type="Number">{{ $totalCatatan }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="KpiValueExpense"><Data ss:Type="Number">{{ (int) $pengeluaranTerbesar }}</Data></Cell>
   </Row>
   <Row ss:Height="12"></Row>

   {{-- Header Tabel Data --}}
   <Row ss:Height="24">
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">No</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Kode Pengeluaran</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Tanggal</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Nama Pengeluaran</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Keterangan</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Dicatat Oleh</Data></Cell>
    <Cell ss:StyleID="ColHeader"><Data ss:Type="String">Nominal (Rp)</Data></Cell>
   </Row>

   @forelse ($pengeluarans as $index => $item)
    <Row ss:Height="20">
     <Cell ss:StyleID="CellCenter"><Data ss:Type="Number">{{ $index + 1 }}</Data></Cell>
     <Cell ss:StyleID="CellKode"><Data ss:Type="String">{{ $item->kode_pengeluaran }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $item->tanggal_pengeluaran ? $item->tanggal_pengeluaran->format('d/m/Y') : '-' }}</Data></Cell>
     <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ htmlspecialchars($item->nama_pengeluaran, ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ htmlspecialchars($item->keterangan ?: '-', ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ htmlspecialchars($item->user ? $item->user->name : '-', ENT_XML1, 'UTF-8') }}</Data></Cell>
     <Cell ss:StyleID="CellCurrencyBold"><Data ss:Type="Number">{{ (int) $item->nominal }}</Data></Cell>
    </Row>
   @empty
    <Row ss:Height="24">
     <Cell ss:MergeAcross="6" ss:StyleID="CellCenter"><Data ss:Type="String">Tidak ada data pengeluaran pada periode ini.</Data></Cell>
    </Row>
   @endforelse

   {{-- Baris Grand Total di Bawah --}}
   <Row ss:Height="24">
    <Cell ss:MergeAcross="5" ss:StyleID="TotalLabel"><Data ss:Type="String">TOTAL PENGELUARAN :</Data></Cell>
    <Cell ss:StyleID="TotalValue"><Data ss:Type="Number">{{ (int) $totalPengeluaran }}</Data></Cell>
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
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>

