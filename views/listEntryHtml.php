<tr>
	<td class='list-entry'>
		<div id='fix_content_show_detail_{{number}}' class='fix-content'>
			<table>	
				{{# contact}}
				<tr>
					<td class='number' rowspan='5' style='vertical-align: top;' width='5%'>
						<div class="ui checkbox">
					    	<input type="checkbox" name='member_list[]' value='{{id}}' class='member_list hidden'>
					    	<label></label>
					    </div>
					</td>
					<td class='profile' rowspan='5' width='25%'>
						<a href='{{image.source}}' target='_blank' onclick='image_popup(this, event)'>
							<img src="{{image.thumbnail}}" class='profile-picture' alt='Profilbild'>
						</a>
					</td>
					<td class='contact_name' width='65%'><b>{{first_name}} {{last_name}}</b></td>
					<td align='right' width='5%'>
						<div class='status {{member.active}}'></div>
					</td>
				</tr>
				<tr>
					<td class='contact-id'> ID: {{id}}</td>
					<td align='right' rowspan='4' style="vertical-align: bottom;">
						<!-- Buttons -->
						{{# userHasCanEdit }}
						<button type='button' class='fluid ui icon mini basic button labeled' value='{{contact.id}}' onclick='edit(this.value)'>
							<i class='edit icon'></i>
							EDIT
						</button>
						{{/ userHasCanEdit }}
						
						<button type='button' class='fluid ui icon mini basic button labeled' id='slide-content-button-{{number}}' value='{{number}}' onclick='expand_content(this.value)'>
							<i class='chevron up down icon'></i>
							DETAILS
						</button>
					</td>
				</tr>
				<tr>
					<td class='birth-date'> Geb.: {{birth_date}}</td>
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
				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="tabs-{{number}}-1">Studiengänge</a>
					<a class="item" data-tab="tabs-{{number}}-2">Fähigkeiten</a>
					<a class="item" data-tab="tabs-{{number}}-3">Notizen</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab='tabs-{{number}}-1'>
					{{# studies}}
						<table style='margin-top: 1rem;'>
							<tr>
								<th colspan='4' style='text-align: center;'>{{course}}</th>
							</tr>
							<tr>
								<td width='25%'>Status: </td>
								<td colspan='3'>
									<span class="study_status">{{status}}</span>
								</td>
							</tr>
							<tr>
								<td>Start: </td>
								<td>{{start}}</td>
								<td width='25%'>Ende: </td>
								<td>{{end}}</td>
							</tr>
						 	<tr>
					 			<td>(Hoch-)Schule: </td>
					 			<td colspan='3'>{{school}}</td>
					 		</tr>
					 		<tr>
					 			<td>Abschluss: </td>
					 			<td colspan='3'>{{degree}}</td>
					 		</tr>
						</table>
					{{/ studies}}
				</div>
				<div class="ui bottom attached tab segment" data-tab='tabs-{{number}}-2'>
					Fähigkeiten <i>(wird noch nicht unterstützt...)</i>
				</div>
				<div class="ui bottom attached tab segment" data-tab='tabs-{{number}}-3'>
					<pre>{{{contact.comment}}}</pre>
				</div>
			</div>
		</div>
	</td>
</tr>