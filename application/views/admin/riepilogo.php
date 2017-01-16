<?php if(is_admin()): ?>
<ul class="submenu">
    <li>
        <a href="<?php echo site_url("/admin"); ?>">Istruttoria</a>
    </li>
	<li>
        <a href="<?php echo site_url("/admin/alert_scadenze"); ?>">Alert di istruttoria</a>
    </li>
	<li class="selected">Dati di riepilogo</li>
     <li>
        <a href="<?php echo site_url("/admin/protocollo"); ?>">Configurazione protocollo</a>
    </li>
</ul>
<?php endif;?>

<h2>Dati pubblicazione</h2>
<h3>Totale imprese pubblicate: 
<?php 

$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;

  ?>
<br>
Edilizia: 
<?php 

$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('pubblicato','1')
				->where('tipo_contratto','Edilizia')
                ->count_all_results();
echo $rowcount;

  ?>
  <br>
Altri settori:
<?php 

$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('pubblicato','1')
				->where('tipo_contratto','Altri settori')
                ->count_all_results();
echo $rowcount;

  ?>
<br>
Almeno un'attestazione SOA:
<?php 

$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('pubblicato','1')
				->where('soa_sino','Yes')
                ->count_all_results();
echo $rowcount;

  ?>
<br>
Prive di attestazione SOA:
<?php 

$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('pubblicato','1')
				->where('soa_sino','Off')
                ->count_all_results();
echo $rowcount;

  ?>
</h3>

<table class="riepilogo">
<tr>
<th>Provincia di Bologna</th>
<th>Provincia di Ferrara</th>
<th>Provincia di Modena</th>
<th>Provincia di Reggio Emilia</th>
<th>Provincia di Rovigo</th>
<th>Provincia di Mantova</th>
<th>Provincia di Forl&igrave-Cesena</th>
<th>Provincia di Parma</th>
<th>Provincia di Piacenza</th>
<th>Provincia di Ravenna</th>
<th>Provincia di Rimini</th>
<th>Altre province</th>
</tr>
<tr>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','BO')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
 ?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','FE')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','MO')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','RE')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','RO')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','MN')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','FC')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','PR')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','PC')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','RA')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('sl_prov','RN')
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$province_cratere = array('BO','MO','PR','RA','RN','FE','RE','RO','MN','FC','PC');
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where_not_in('sl_prov', $province_cratere)
				->where('pubblicato','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
</tr>
</table>
<h2>Dati istruttoria (calcolati sulle imprese che hanno caricato la form firmata digitalmente sul sistema)<h2>
<h3>DURC</h3>
<table class="riepilogo">
<tr>
<th>Richieste in attesa</th>
<th>Durc regolare</th>
<th>Durc non regolare</th>
<th>Imprese non controllate</th>
</tr>
<tr>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('durc','1')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('durc','2')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('durc','3')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('durc','0')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
</tr>
</table>
<h3>Protesti</h3>
<table class="riepilogo">
<tr>
<th>Imprese protestate</th>
<th>Imprese non protestate</th>
<th>Imprese non controllate</th>
</tr>
<tr>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('protesti','2')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('protesti','3')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('protesti','0')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
</tr>
</table>
<h3>Antimafia</h3>
<table class="riepilogo">
<tr>
<th>Imprese conformi</th>
<th>Imprese non conformi</th>
<th>Imprese non controllate</th>
</tr>
<tr>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('antimafia','2')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('antimafia','3')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('antimafia','0')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
</tr>
</table>
<h3>5bis</h3>
<table class="riepilogo">
<tr>
<th>Imprese soggette 5bis</th>
<th>Imprese non soggette 5bis</th>
</tr>
<tr>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('5bis','1')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
<td>
<?php 
$rowcount=	$this->db
                ->select('*')
                ->from('dichiaraziones')
				->where('5bis','0')
				->where('uploaded','1')
                ->count_all_results();
echo $rowcount;
?>
</td>
</tr>
</table>

