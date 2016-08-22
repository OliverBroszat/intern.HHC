<tr>
	<td class='list-entry'>
		<div id='fix_content_show_detail_{{number}}' class='fix-content'>
			<table>	
				{{# contact}}
				<tr>
					<td class='number' rowspan='4' style='vertical-align: top;' width='5%'>
						<div class="ui checkbox">
					    	<input type="checkbox" name='member_list[]' value='{{id}}' class='member_list hidden'>
					    	<label></label>
					    </div>

						<!-- <input type='checkbox' name='member_list[]' value='{{id}}' class='member_list'> -->
						<br>----<br>
						{{number}}
						<br>----<br>
						{{id}}
					</td>
					<td class='profile' rowspan='4' width='20%'>
						IMG
					</td>
					<td class='contact_name' width='70%'><b>{{first_name}} {{last_name}}</b></td>
					<td align='right' width='5%'><div class='status {{member.active}}'></div></td>
				</tr>
				{{/ contact}}
				{{# member}}
				<tr>
					<td class='position'> Position: {{position}}</td>
				</tr>
				<tr>
					<td class='ressort' >Ressort: {{name}}</td>
				</tr>
				{{/ member}}
				<tr>
					<td>
						<button type='button' class='fluid ui icon mini basic button' value='{{number}}' onclick='expand_content(this.value)'>
							<i class='eye icon'></i>
							DETAILS
						</button>
					</td>
					<td>
						<button type='button' class='ui icon mini basic button labeled' value='{{contact.id}}' onclick='edit(this.value)'>
							<i class='edit icon'></i>
							Edit
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div id='slide_content_show_detail_{{number}}' class='detail-content' style='display: none; overflow: hidden; position: relative;'>
			<div class='info-list'>
				<div class='data-block'>
					<div class='data-set'>
						<span class='data-set-title'>Hinterlegte Adressen</span>
						<div class='scroll-list'>
							<table>
								{{# addresses}}
									<tr>
										<td width='10%'>{{description}}</td>
										<td>
											{{street}} {{number}} {{addr_extra}}<br>
											{{postal}} {{city}}
										</td>
									</tr>
								{{/ addresses}}
							</table>
						</div>
					</div>
					<div class='data-set'>
						<span class='data-set-title'>E-Mail</span>
						<div class='scroll-list'>
							<table>
								{{# mails}}
									<tr>
										<td width='10%'>{{description}}</td>
										<td>
											<a href='mailto:{{address}}'>{{address}}</a>
										</td>
									</tr>
								{{/ mails}}
							</table>
						</div>
					</div>
					<div class='data-set'>
						<span class='data-set-title'>Telefonnummern</span>
						<div class='scroll-list'>
							<table>
								{{# phones}}
									<tr>
										<td width='10%'>{{description}}</td>
										<td>
											<a href='tel:{{number}}'>{{number}}</a>
										</td>
									</tr>
								{{/ phones}}
							</table>
						</div>
					</div>
				</div>
				<div class='data-block'>
					<div class='data-set'>
						<span class='data-set-title'>HHC-Mitgliedschaft</span>
						<div class='scroll-list'>
							<table>
								<tr>
									<td width='10%'>Beitritt</td>
									<td>{{member.joined}}</td>
								</tr>
								<tr>
									<td width='10%'>Austritt</td>
									<td>{{member.left}}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>


			<div id='tabs-{{number}}'>
				<ul>
					<li><a href='#tabs-{{number}}-1'>Studiengänge</a></li>
					<li><a href='#tabs-{{number}}-2'>Fähigkeiten</a></li>
					<li><a href='#tabs-{{number}}-3'>Notizen</a></li>
				</ul>
				<div id='tabs-{{number}}-1'>
					{{# studies}}
						<table style='margin-top: 1rem;'>
							<tr>
								<th colspan='2' style='text-align: center;'>{{course}}</th>
							</tr>
							<tr>
								<td width='30%'>Status: </td>
								<td>
									<span class="study_status">{{status}}</span>
								</td>
							</tr>
						 	<tr>
					 			<td>(Hoch-)Schule: </td>
					 			<td>{{school}}</td>
					 		</tr>
					 		<tr>
					 			<td>Abschluss: </td>
					 			<td>{{degree}}</td>
					 		</tr>
						</table>
					{{/ studies}}
				</div>
				<div id='tabs-{{number}}-2'>
					Fähigkeiten <i>(wird noch nicht unterstützt...)</i>
				</div>
				<div id='tabs-{{number}}-3'>
					{{{contact.comment}}}
				</div>
			</div>
			<script>$(function() {$("#tabs-{{number}}").tabs();});</script>
		</div>
	</td>
</tr>