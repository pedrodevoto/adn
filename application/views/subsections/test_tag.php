<?php
header("Cache-Control: private");
header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=Test_Tag_".$advertiser);
?>
<html>
<body>
	<h2>Name:</h2> 
	<span class="header">Test Tag <?=$advertiser?> <font color="#E60000">Do not modify</font></span>
	<br />
	<div id="tag_preview_div_4746058_336"> 
		<table width="<?=$width?>" border="0" height="<?=$height?>" style="background: white; font-size:12; font-family: Arial, Verdana; color: #585858; border:solid 1px #7F9DB9;">
			<tbody>
				<tr>
					<td>
						<IFRAME FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH=<?=$width?> HEIGHT=<?=$height?> SRC="http://ad.adnetwork.net/st?ad_type=iframe&amp;ad_size=<?=$width?>x<?=$height?>&amp;section=<?=$section?>&amp;pub_url=${PUB_URL}"></IFRAME>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br />
	<h2>Tag content:</h2>
	<br />
	<textarea style="width:100%; height:200px; background: white; font-size: 12">
		<!-- BEGIN STANDARD TAG - <?=$width?> x <?=$height?> - Test Tag <?=$advertiser?> - DO NOT MODIFY -->
		<IFRAME FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH=<?=$width?> HEIGHT=<?=$height?> SRC="http://ad.adnetwork.net/st?ad_type=iframe&amp;ad_size=<?=$width?>x<?=$height?>&amp;section=<?=$section?>&amp;pub_url=${PUB_URL}"></IFRAME>
		<!-- END TAG -->
	</textarea>
	<br /><br /><br />Adding a custom query string:  insert <b>&YOUR_CUSTOM_QUERY_STRING</b> at the end of the SRC attribute in the tag above.<br/>Use the &amp;PUB_URL provided at the end of the SRC attribute in the tag above when another ad server may<br/>inadvertently mask Referring URL from Right Media. Please refer to <a href='https://kb.yieldmanager.com/article.php?id=871' target='_new'>KB Article</a> for further details.
</body>
</html>