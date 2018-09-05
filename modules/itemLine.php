var itemLine = "<div class='item'>\
            	<div class='itemLeft'>\
                	<table class='itemTable' width='100%' cellpadding='0' cellspacing='0'>\
                        <tr>\
							<td class='lineNum'></td><input type='hidden' id='lineNumT' name='lineNumT[]'>\
                            <td class='tdItemCode'>\
								<input id='codigo_articulo' type='text' class='itemCode' placeholder='CODIGO' required>\
							</td>\
							<td class='tdItemName'>\
								<input type='text' class='itemName' placeholder='ARTICULO' required>\
                            </td>\
                        </tr>\
                    </table>\
                </div>\
                <div class='itemRight'>\
                	<table class='itemTable' width='100%' cellpadding='0' cellspacing='0'>\
                        <tr>\
                            <td class='tdlPrice'>$ <input type='text' id='listPUser' class='listPrice' style='width:calc(100% - 10px);' onKeyPress='return numeros(event)'></td>\
                            <td class='tdlPrice' hidden>$ <input id='listRsv' type='text' class='listPrice_user' style='width:calc(100% - 10px);' onKeyPress='return numeros(event)'></td>\
                            <td class='tdCurrency'>\
                            	<select class='currency' required>\
                                	<option value='' selected disabled>-</option>\
                                    <?php
									foreach($currencies as $currency) {
										echo "<option value='".$currency["ISOCurrCod"]."'>".$currency["ISOCurrCod"]."</option>";
									}
									?>
                                </select><input type='hidden' class='lineCur'>\
                            <td class='tdDiscount'>\
								<select class='discount' required>\
                                	<?php
									$queryDisc = "SELECT Code, Name, U_discount FROM [@DISCOUNT] ORDER BY Name ASC";
									$resultDisc = mssql_query($queryDisc);
									while ($rowDisc = mssql_fetch_assoc($resultDisc)) {
										$selected = "";
										if ($rowDisc["U_discount"] == 1) {
											$selected = "selected";
										}
										echo "<option value='".$rowDisc["Name"]."' $selected>".$rowDisc["Name"]."</option>";
									}
									?>
                                </select>\
                            </td>\
                            <td class='tduPrice'><div class='finalPriceDiv'>-</div><input type='hidden' class='finalPrice'></td>\
                            <td class='tdQuant'><input class='quantity' type='number' value='1' min='.1' style='width:50px !important' step='any' required></td>\
							<td class='tdnPrice'><div class='linePriceDiv' style='display: inline-block;'>-</div> <span class='chCur'></span><input type='hidden' class='linePrice' name='linePrice[]'></td>\
                            <td Class='tdRemove'><img src='images/remove-icon.png' class='remove' alt='Eliminar partida' title='Eliminar partida'></td>\
                        </tr>\
                    </table>\
                </div>\
				<div class='itemLeft'>\
					<div class='stockTriggerDiv'>\
						<table class='itemTable'>\
							<tr>\
								<td>UMV</td>\
								<td class='tdUniMed'>\
									<select class='uniMed' required>\
										<option value='' selected disabled>-</option>\
										<?php
										$queryUMV = "SELECT SalUnitMsr FROM OITM GROUP BY SalUnitMsr ORDER BY SalUnitMsr";
										$resultUMV = mssql_query($queryUMV);
										while ($rowUMV = mssql_fetch_array($resultUMV)) {
											echo "<option value='".$rowUMV["SalUnitMsr"]."'>".$rowUMV["SalUnitMsr"]."</option>";
										}
										?>
									</select>\
								</td>\
								<td>Entrega</td>\
								<td class='tdDelivery'>\
                                	<input type='text' class='delivery' required></input>\
								</td>\
								<td class=''>\
									Marca: <span class='brand'></span><input type='hidden' class='FirmName'></input>\
								</td>\
							</tr>\
						</table>\
					</div>\
				</div>\
				<div class='itemRight'>\
					<table class='itemTable'>\
						<tr>\
							<td class='tdComment'>Comentario (250 max.)</td>\
							<td class='tdLineRemark'><input type='text' maxlength='150' class='lineRemark'></td>\
						</tr>\
					</table>\
				</div>\
               <div class='itemLeft' <?php if($_SESSION["discounts"] != "Y") {echo "style='visibility:hidden'";}?>>\
					<table class='itemTable'>\
						<tr>\
							<td>Costo</td>\
							<td><div class='lastCostDiv'>-</div><input type='hidden' class='lastCost'><input type='hidden' class='lastCur'></td>\
							<td>Descuento a otorgar</td>\
							<td><input type='number' min='0' max='100' step='any' style='display: inline-block; width:60px;' class='lineDisc' value='0'>%</td>\
							<td>Utilidad</td>\
							<td><div class='profitDiv'>-</div></td>\
						</tr>\
					</table>\
				</div>\
               <div class='itemRight'>\
					<table class='itemTable'>\
						<tr>\
							<td>Descuento otorgado</td>\
							<td><div class='viewDisc' style='display: inline-block;'>0</div>%</td>\
							<td>Precio unitario con descuento</td>\
							<td><div class='fdPriceDiv'>-</div></td>\
                            <td>Importe con descuento</td>\
							<td style='font-weight:bold; text-align:right'><div class='ndPriceDiv' style='display: inline-block;'>-</div> <span class='chCur'></span><input type='hidden' class='lineDiscPrice' name='lineDiscPrice[]'></td>\
						</tr>\
					</table>\
				</div>\
                <div class='stockDiv'>\
                <div class='stockLeft'>\
                	<table width='100%' border='0' cellspacing='0' cellpadding='0'>\
                      <tbody>\
                        <tr>\
                          <td colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#3e631a'>Existencia por almac&eacute;n (Total <div class='oHTotal'>-</div>)</td>\
                        </tr>\
                        <tr style='color:#3e631a'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
                        </tr>\
                        <tr style='color:#3e631a'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oH".$whCode."'>-</div><button type='button' class='batch".$whCode."'>Lote</button></td>";
							}
							?>
                        </tr>\
                      </tbody>\
                    </table>\
                </div>\
                <div class='stockRight'>\
                	<table width='100%' border='0' cellspacing='0' cellpadding='0'>\
                      <tbody>\
                        <tr>\
                          <td colspan='<?php echo count($whCodes); ?>' class='qSubSec' style='color:#a50000'>Pendiente por recibir en almac&eacute;n</td>\
                        </tr>\
                        <tr style='color:#a50000'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td>".$whNames[$i]."</td>";
							}
							?>
                        </tr>\
                        <tr style='color:#a50000'>\
							<?php
							foreach ($whCodes as $i => $whCode) {
								echo "<td><div class='oO".$whCode." oTot'><div class='divOOSD'>\
								</div><span>-</span></div></td>";
							}
							?>
                        </tr>\
                      </tbody>\
                    </table>\
                </div>\
                </div>\
                <div class='batch-div'>\
                	<div class='batch-closer'>\
                		<i class='fa fa-close'>\
                		</i>\
                	</div>\
				  <div>\
				    <table>\
				    </table>\
				  </div>\
				</div>\
            </div>";

// Set Line Number
var setLineNumber = function() {
	$(".item").each(function() {
		var ind = $(".item").index(this);
		$(this).find(".lineNum").html(ind + 1);
		$(this).find("#lineNumT").val(ind + 1);
		$(this).find(".itemCode").attr("name", "itemCode["+(ind)+"]");
		$(this).find(".itemName").attr("name", "itemName["+(ind)+"]");
		$(this).find(".listPrice").attr("name", "listPrice["+(ind)+"]");
		$(this).find(".currency").attr("name", "currency["+(ind)+"]");
        $(this).find(".lineCur").attr("name", "lineCur["+(ind)+"]");
		$(this).find(".discount").attr("name", "disc["+(ind)+"]");
        $(this).find(".finalPrice").attr("name", "finalPrice["+(ind)+"]");
		$(this).find(".quantity").attr("name", "quant["+(ind)+"]");
		$(this).find(".uniMed").attr("name", "umv["+(ind)+"]");
		$(this).find(".delivery").attr("name", "deliv["+(ind)+"]");
        $(this).find(".FirmName").attr("name", "FirmName["+(ind)+"]");
		$(this).find(".lineRemark").attr("name", "lineRemark["+(ind)+"]");
		$(this).find(".lastCost").attr("name", "lastCost["+(ind)+"]");
		$(this).find(".lastCur").attr("name", "lastCur["+(ind)+"]");
		$(this).find(".lineDisc").attr("name", "lineDisc["+(ind)+"]");

		$(this).find(".batch-div").attr("id", "batch-"+(ind));
	});
	$("#iQLines").html($("#itemContainer").children(".item").length);
};

var addItem = function() {
	$("#itemContainer").append(itemLine);
	$(".chCur").html(DocCur);
	setLineNumber();
	$("#saveQuote").prop("disabled", false);
	validateSAP();
};

$("#addItem").on("click", addItem);

$(document).on("input", ".itemCode", function() {
	var ind = $(".itemCode").index(this);
	$(".itemCode:eq("+ind+")").autocomplete({
		minLength: 3,
		source: "includes/searchProd.php?by=code",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".itemCode:eq("+ind+")").val(ui.item.value);
			} else {
				$(".itemCode:eq("+ind+")").val(ui.item.value);
			}
			getProdDetails(ind);
		},
		close: function() {
			getProdDetails(ind);
		}
	});
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
	if ($(".itemCode:eq("+ind+")").val().length >= 3) {
		getProdDetails(ind);
	}
});

$(document).on("input", ".itemName", function() {
	var ind = $(".itemName").index(this);
	$(".itemName").autocomplete({
		minLength: 3,
        source: "includes/searchProd.php?by=name",
		select: function(event, ui) {
			var origEvent = event;
			while (origEvent.originalEvent !== undefined) {
				origEvent = origEvent.originalEvent;
			}
			if (origEvent.type == "click") {
				$(".itemName:eq("+ind+")").val(ui.item.value);
			} else {
				$(".itemName:eq("+ind+")").val(ui.item.value);
			}
			getProdCode(ind);
		},
		close: function() {
			getProdCode(ind);
		}
    });
	if ($(".itemName:eq("+ind+")").val().length >= 3) {
		getProdCode(ind);
	}
});

$(document).on("input", ".delivery", function() {
	var input = $(this);
	var start = input[0].selectionStart;
	$(this).val(function (_, val) {
		return val.toUpperCase();
	});
	input[0].selectionStart = input[0].selectionEnd = start;
});

// Remove itemLine
$(document).on('click', '.remove', function() {
	$(this).closest('.item').hide("fast").delay(100).queue(function(){
    	$(this).remove();
		if($("#itemContainer").children(".item").length == 0) {
			$("#saveQuote").prop("disabled", true);
		}
        setLineNumber();
        calculateSubs();
        validateSAP();
    });
    
});

//////////////////////================================= Get product details =================================//////////////////////
var getProdDetails = function(ind) {
	var CardCode = $("#CardCode").val();
	var code = $(".itemCode:eq("+ind+")").val();
	if (code.length >= 3) {
		var lastCost = 0;
		var lPrice = 0;
		var fPrice = 0;
		var nPrice = 0;
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?code="+encodeURI(code) + "&itemcode=" + CardCode,
			dataType: "json",
			cache: false,                
			success: function(prodDetail){

				if(prodDetail == true){
					swal("Producto bloqueado por operaciones. Consultar con departamento");
				}

				$(".itemName:eq("+ind+")").val(prodDetail["ItemName"]);
				lPrice = parseFloat(prodDetail["Price"]);
				lastCost = parseFloat(prodDetail["LastPurPrc"]);
				fPrice = lPrice - (lPrice * $(".discount:eq("+ind+")").val() / 100);
				fPrice = parseFloat(fPrice);
				nPrice = fPrice * $(".quantity:eq("+ind+")").val();
				nPrice = parseFloat(nPrice);
				$(".listPrice:eq("+ind+")").val(lPrice);
				$("#listRsv").val(lPrice);
				$(".finalPriceDiv:eq("+ind+")").html("$"+localeString(fPrice));
				$(".finalPrice:eq("+ind+")").val(fPrice);
				$(".linePriceDiv:eq("+ind+")").html("$"+localeString(nPrice));
				$(".linePrice:eq("+ind+")").val(nPrice);
				$(".currency:eq("+ind+")").val(prodDetail["Currency"]).trigger("change");
				$(".uniMed:eq("+ind+")").val(prodDetail["SalUnitMsr"]);
				$(".brand:eq("+ind+")").html(prodDetail["FirmName"]);
                $(".FirmName:eq("+ind+")").val(prodDetail["FirmName"]);
                $(".lastCostDiv:eq("+ind+")").html("$"+localeString(lastCost)+" "+prodDetail["LastPurCur"]);
                $(".lastCost:eq("+ind+")").val(lastCost);
                $(".lastCur:eq("+ind+")").val(prodDetail["LastPurCur"]);
				if(prodDetail["isSAP"] == 'Y') {
					$(".lineNum:eq("+ind+")").addClass("isSAP");
				} else {
					$(".lineNum:eq("+ind+")").removeClass("isSAP");
				}
				// Write stock
				var totOnHand = 0;
				<?php
				//foreach($whCodes as $whCode) {
					echo "var whcodes = ".json_encode($whCodes).";";
					echo "var whcode = ".$whCode.";";
					?>

					whcodes.forEach(function(e){

						if(prodDetail["e" + e] == -1){
							$('.oH' + e + ":eq("+ind+")").html("<i class='fa fa-ban' aria-hidden='true'></i>");
							$('.oO' + e + ":eq("+ind+")").find('span').html("<i class='fa fa-ban' aria-hidden='true'></i>");
							$('.oO' + e + ":eq("+ind+")").find('.divOOSD').html("<i class='fa fa-ban' aria-hidden='true'></i>");
						}else{
							//prodDetail['qu' + e] == null ? $('.batch' + e).attr('disabled', 'disabled') : $('.batch' + e).removeAttr('disabled');
							$('.batch' + e).attr('onclick', 'getLote(\"' + e + '\", \"' + prodDetail["ItemCode"] + '\", this)');
							$('.oH' + e + ":eq("+ind+")").html(parseInt(prodDetail["e" + e]));
							totOnHand += parseInt(prodDetail["e" + e]);
							$('.oO' + e + ":eq("+ind+")").find('span').html(parseInt(prodDetail["p" + e]));
							$('.oO' + e + ":eq("+ind+")").find('.divOOSD').html(prodDetail["qo" + e]);
						}
					});

					$(".batch-closer").attr("onclick", "closeBatch()");

				<?php
				//}

				?>
				$(".oHTotal:eq("+ind+")").html(totOnHand);
				validateSAP();
			}
		});
	}
};

var getLote = function(whcode, item, event){
	var origin = $(event);
	//AJAX
	$.ajax({
		method: "POST",
		url: 'includes/ajaxBatch.php',
		data: {
			'whcode': whcode,
			'item': item
		},
		success: function(response){
			if(response.batches){
				origin.parents(".item").find(".batch-div table").empty();
				var html_batch = "<tr><th>ID Lote</th><th>Cantidad</th></tr>";
				response.batches.forEach(function(e){
					html_batch += ("<tr><th>" + e.BatchID + "</th><th>" + e.Quantity + "</th></tr>");
				});
				//$(".batch-div table").append(html_batch);
				origin.parents(".item").find(".batch-div table").append(html_batch);

				origin.parents(".item").find(".batch-div").show();
			}else{
				origin.parents(".item").find(".batch-div table").empty();
				origin.parents(".item").find(".batch-div table").append("<tr><td><strong>NO HAY LOTES</strong></td></tr>");
				origin.parents(".item").find(".batch-div").show();
			}
		}
	})
};
var closeBatch = function(){
	$(".batch-div").hide();
}
var getInventory = function(ind) {
	var code = $(".itemCode:eq("+ind+")").val();
	if (code.length >= 3) {
		var lastCost = 0;
		var lPrice = 0;
		var fPrice = 0;
		var nPrice = 0;
		$.ajax({
			type: "GET",
			url: "includes/prodDetails.php?code="+encodeURI(code),  
			dataType: "json",
			cache: false,                
			success: function(prodDetail){
				lastCost = parseFloat(prodDetail["LastPurPrc"]);
				$(".lastCostDiv:eq("+ind+")").html("$"+localeString(lastCost)+" "+prodDetail["LastPurCur"]);
                $(".lastCost:eq("+ind+")").val(lastCost);
                $(".lastCur:eq("+ind+")").val(prodDetail["LastPurCur"]).trigger("change");
				if(prodDetail["isSAP"] == 'Y') {
					$(".lineNum:eq("+ind+")").addClass("isSAP");
				} else {
					$(".lineNum:eq("+ind+")").removeClass("isSAP");
				}
				// Write stock
				var totOnHand = 0;
				<?php
				foreach($whCodes as $whCode) {
					echo $prodDetail['e'.$whCode];
					if($prodDetail['e'.$whCode] == -1.0){
						echo "$('.oH".$whCode.":eq('+ind+')').html('X');";
						echo "totOnHand += 0;";
						echo "$('.oO".$whCode.":eq('+ind+')').find('span').html('X'));";
						echo "$('.oO".$whCode.":eq('+ind+')').find('.divOOSD').html('X');";
					}
					else{
						echo "$('.oH".$whCode.":eq('+ind+')').html(parseInt(prodDetail['e".$whCode."']));";
						echo "totOnHand += parseInt(prodDetail['e".$whCode."']);";
						echo "$('.oO".$whCode.":eq('+ind+')').find('span').html(parseInt(prodDetail['p".$whCode."']));";
						echo "$('.oO".$whCode.":eq('+ind+')').find('.divOOSD').html(prodDetail['qo".$whCode."']);";
					}
				}
				?>
				$(".oHTotal:eq("+ind+")").html(totOnHand);
				validateSAP();
			}
		});
	}
};

var getProdCode = function(ind) {
	var name = $(".itemName:eq("+ind+")").val();
	if (name.length >= 3) {
		$.ajax({
			type: "GET",
			url: "includes/prodCode.php?name="+encodeURI(name),
			dataType: "json",
			cache: false,
			success: function(prodCode){
				if (prodCode["ItemCode"] != null && prodCode["ItemCode"] != "") {
					$(".itemCode:eq("+ind+")").val(prodCode["ItemCode"]);
					getProdDetails(ind);
				}
			}
		});
	}
};

// Calculate each Line
var calculateLine = function(ind) {
	var nPrice = 0;
	var lPrice = $(".listPrice:eq("+ind+")").val();
	var lCurr = $(".currency:eq("+ind+")").val();
	var factor = $(".discount:eq("+ind+")").val();
	var fPrice = lPrice - (lPrice * factor / 100);
	var quant = $(".quantity:eq("+ind+")").val();
    var fdPrice = 0;
    var ndPrice = 0;
    var lDisc = $(".lineDisc:eq("+ind+")").val();
    fdPrice = fPrice - (fPrice * lDisc / 100);
	if (lCurr == "USD" && DocCur == "MXN") {
		nPrice = fPrice * quant * USD;
        ndPrice = fdPrice * quant * USD;
	} else if (lCurr == "MXN" && DocCur == "USD") {
		nPrice = fPrice * quant / USD;
        ndPrice = fdPrice * quant / USD;
	} else {
		nPrice = fPrice * quant;
        ndPrice = fdPrice * quant;
	}
    // Calc Profit
    var lastCost = $(".lastCost:eq("+ind+")").val();
	var lastCur = $(".lastCur:eq("+ind+")").val();
	var profit = 0;
	if (lastCur == "USD" && lCurr == "MXN") {
		profit = ((fdPrice / lastCost * USD	)-1) * 100;
	} else if (lastCur == "MXN" && lCurr == "USD") {
		profit = ((fdPrice * USD / lastCost)-1) * 100;
	} else {
		profit = ((fdPrice / lastCost)-1) * 100;
	}
    $(".viewDisc:eq("+ind+")").html(localeString(lDisc));
	$(".profitDiv:eq("+ind+")").html(localeString(profit)+"%");
	$(".finalPriceDiv:eq("+ind+")").html("$"+localeString(fPrice));
	$(".finalPrice:eq("+ind+")").val(fPrice);
	$(".linePriceDiv:eq("+ind+")").html("$"+localeString(nPrice));
	$(".linePrice:eq("+ind+")").val(nPrice);
    $(".fdPriceDiv:eq("+ind+")").html("$"+localeString(fdPrice));
    $(".ndPriceDiv:eq("+ind+")").html("$"+localeString(ndPrice));
    $(".lineDiscPrice:eq("+ind+")").val(ndPrice);
	calculateSubs();
}




