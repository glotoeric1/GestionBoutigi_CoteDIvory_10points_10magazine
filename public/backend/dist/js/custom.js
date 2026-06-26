//Paiement Indirect and Paiement d'avancement
jQuery('select[name="tva_id"]').on("change", function () {
    var options = parseFloat(jQuery(this).val());
    var total_ht = parseFloat($("#total").val());
    var total_tva = 0;
    var total_ttc = 0;

    if (options == "0.05" || options == "0.18") {
        total_tva = total_ht * options;
        total_ttc = total_ht + total_tva;
        $("#total_tva").val(total_tva);
        $("#total_ttc").val(total_ttc);
    } else {
        $("#total_tva").val("");
        $("#total_ttc").val("");
    }
});
//Pour vente et dette
jQuery('select[name="tva"]').on("change", function () {
    var options = parseFloat(jQuery(this).val());
    var total_ht = parseFloat($("#montantApayer").val());
    var total_tva = 0;
    var total_ttc = 0;

    if (options == "0.05" || options == "0.18") {
        total_tva = total_ht * options;
        total_ttc = total_ht + total_tva;
        $("#total_tva").val(total_tva);
        $("#total_ttc").val(total_ttc);
        $("#tva_total").removeClass("d-none");
    } else {
        $("#tva_total").addClass("d-none");
    }
});

function showForm() {
    var form = document.querySelector("#form");
    var btnCls = document.querySelector("#btnClose");
    var btnOpen = document.querySelector("#btnOpen");

    form.classList.remove("d-none");
    btnCls.classList.remove("d-none");
    btnOpen.classList.add("d-none");
}

function closeForm() {
    var form = document.querySelector("#form");
    var btnCls = document.querySelector("#btnClose");
    var btnOpen = document.querySelector("#btnOpen");

    form.classList.add("d-none");
    btnCls.classList.add("d-none");
    btnOpen.classList.remove("d-none");
}

function oldClient() {
    //alert("Hello 1");
    var idOld = document.getElementById("oldClient");
    var idNew = document.getElementById("newClient");
    idOld.classList.remove("d-none");
    idNew.classList.remove("d-block");
    idNew.classList.add("d-none");
    idOld.classList.add("row");

    document.getElementById("cltOption").value = "Old";
}

function newClient() {
    var idOld = document.getElementById("oldClient");
    var idNew = document.getElementById("newClient");
    idOld.classList.add("d-none");
    idOld.classList.remove("d-block");
    idNew.classList.remove("d-none");
    idNew.classList.add("row");

    document.getElementById("cltOption").value = "New";
}

//Get all students information
/*
jQuery('#idvente').click(function(){
    //var studentId = jQuery(this).val();
    var id_prod = jQuery(".idcl").val();
    //console.log(id_prod);
    if(id_prod)
    {
      jQuery.ajax({
          url : '/orders/' +id_prod,
          type : "GET",
          dataType : "json",
          success:function(data)
          {
            jQuery.each(data, function(key,value){
                console.log('New ID : '+value.clientId);
                
              });
 
          }, error: function(xhr, status, error) {
            console.error(xhr);
          }
      });
    }
    else
    {
        $("#quantite").val('');
        $("#prix").val('');
        $("#total").val('');
    }
});
*/

//get data
function CalculateBenefice(
    idfirst,
    idSecond,
    idThird,
    idtotalAchat,
    idTotalVente,
    idBenefice
) {
    var total_en_detail = parseInt(document.getElementById(idfirst).value);
    var Total_en_gros = parseInt(document.getElementById(idSecond).value);

    if (
        typeof Total_en_gros === "number" &&
        typeof total_en_detail === "number" &&
        !isNaN(Total_en_gros) &&
        !isNaN(total_en_detail)
    ) {
        let benefice_en_gros = Total_en_gros - total_en_detail;
        var totalGro = document.getElementById(idThird);
        totalGro.value = benefice_en_gros;
        if (benefice_en_gros > 0) {
            totalGro.style.borderColor = "green";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        } else if (benefice_en_gros == 0) {
            totalGro.style.borderColor = "#ffc107";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        } else {
            totalGro.style.borderColor = "red";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        }
    }

    total_en_detail = parseInt(document.getElementById(idtotalAchat).value);
    Total_en_gros = parseInt(document.getElementById(idTotalVente).value);
    if (
        typeof Total_en_gros === "number" &&
        typeof total_en_detail === "number" &&
        !isNaN(Total_en_gros) &&
        !isNaN(total_en_detail)
    ) {
        let benefice_en_gros = Total_en_gros - total_en_detail;
        var totalGro = document.getElementById(idBenefice);
        totalGro.value = benefice_en_gros;
        if (benefice_en_gros > 0) {
            totalGro.style.borderColor = "green";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        } else if (benefice_en_gros == 0) {
            totalGro.style.borderColor = "#ffc107";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        } else {
            totalGro.style.borderColor = "red";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        }
    }
}

function findTotalAchat() {
    var prix = parseInt(document.getElementById("prix_achat").value);
    var qte = parseInt(document.getElementById("qte_achat").value);

    if (
        typeof prix === "number" &&
        typeof qte === "number" &&
        !isNaN(prix) &&
        !isNaN(qte)
    ) {
        document.getElementById("total_achat").value = prix * qte;
        //CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
        //totalQteEnDetail("qte_achat", "qte_par_carton")
    }
}

function findTotalVenteEnGros() {
    var prix = parseInt(document.getElementById("prix_vente_en_gros").value);
    var qte = parseInt(document.getElementById("qte_achat").value);
    if (
        typeof prix === "number" &&
        typeof qte === "number" &&
        !isNaN(prix) &&
        !isNaN(qte)
    ) {
        document.getElementById("Total_en_gros").value = prix * qte;
        //CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
    }
}

function findTotalVenteEnDetail() {
    var prix = parseInt(document.getElementById("prix_achat").value);
    var qte = parseInt(document.getElementById("qte_total_en_detail").value);
    if (typeof prix === "number" && typeof qte === "number") {
        document.getElementById("Total_en_detail").value = prix * qte;

        var total_en_detail = parseInt(
            document.getElementById("total_achat").value
        );
        var Total_en_gros = parseInt(
            document.getElementById("Total_en_gros").value
        );
        if (
            typeof Total_en_gros === "number" &&
            typeof total_en_detail === "number"
        ) {
            document.getElementById("Total_benefice_en_gros").value =
                Total_en_gros - total_en_detail;
        }
        CalculateBenefice(
            "total_achat",
            "Total_en_gros",
            "Total_benefice_en_gros",
            "total_achat",
            "Total_en_detail",
            "Total_benefice_en_detail"
        );
    }
}

function beneficeEnGros() {
    var total_en_detail = parseInt(
        document.getElementById("Total_en_detail").value
    );
    var Total_en_gros = parseInt(
        document.getElementById("Total_en_gros").value
    );
    if (
        typeof Total_en_gros === "number" &&
        typeof total_en_detail === "number"
    ) {
        //var somme   =Total_en_gros-total_en_detail;
        document.getElementById("Total_benefice_en_gros").value =
            Total_en_gros - total_en_detail;
    }
}

function totalQteEnDetail(idQte1, idQte2) {
    //var qte_par_carton      = parseInt(document.getElementById('qte_par_carton').value);
    //var qte_achat           = parseInt(document.getElementById('qte_achat').value);

    var qte_par_carton = parseInt(document.getElementById(idQte1).value);
    var qte_achat = parseInt(document.getElementById(idQte2).value);
    if (typeof qte_achat === "number" && typeof qte_par_carton === "number") {
        var somme = qte_achat * qte_par_carton;
        document.getElementById("qte_total_en_detail").value = somme;
    }
}

function CalQteDetail() {
    totalQteEnDetail("qte_achat", "qte_par_carton");
}

function CalculateBalanceBeforePay() {
    //alert("Hello");
    var montantApayer = parseFloat(
        document.getElementById("montantApayer").value
    );
    var montantDonner = parseFloat(
        document.getElementById("montantDonner").value
    );
    var total_ttc = parseFloat(document.getElementById("total_ttc").value);
    var total_tva = parseFloat(document.getElementById("total_tva").value);
    var tva = document.getElementById("tva").value;
    var mon = document.getElementById("montantDonner");

    if (
        typeof montantApayer === "number" &&
        typeof montantDonner === "number" &&
        !isNaN(montantApayer) &&
        !isNaN(montantDonner)
    ) {
        let benefice_en_gros = 0;
        if (tva == "") {
            benefice_en_gros =  montantApayer - montantDonner;
        } else {
            benefice_en_gros = total_ttc - montantDonner;
        }
        var totalGro = document.getElementById("restant");
        totalGro.value = benefice_en_gros;

        if (totalGro.value === 0) {
            totalGro.style.borderColor = "green";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        } else {
            totalGro.style.borderColor = "red";
            totalGro.style.borderWidth = "3px";
            totalGro.style.fontWeigth = "bold";
        }

        // else if (totalGro.value == 0) {
        //     totalGro.style.borderColor = "#ffc107";
        //     totalGro.style.borderWidth = "3px";
        //     totalGro.style.fontWeigth = "bold";
        // }
    }
}

function totalEnDetail() {
    var qte_total_en_detail = parseFloat(
        document.getElementById("qte_total_en_detail").value
    );
    var prix_vente_unitaire = parseFloat(
        document.getElementById("prix_vente_unitaire").value
    );
    if (
        typeof prix_vente_unitaire === "number" &&
        typeof qte_total_en_detail === "number"
    ) {
        document.getElementById("Total_en_detail").value =
            prix_vente_unitaire * qte_total_en_detail;
    }
}

var priceCells = document.getElementsByClassName("totalTopay");
//returns a list with all the elements that have class 'priceCell'
var total = 0;
//loop over the cells array and add to total price
for (var i = 0; i < priceCells.length; i++) {
    var thisPrice = parseFloat(priceCells[i].innerHTML.replace(/[^\d]/g, "")) || 0;
     //get inner text of this cell in number format
    total = total + thisPrice;
}
//total = total.toFixed(2); //give 2 decimal points to total - prices are, e.g 59.80 not 59.8
document.getElementById("montantApayer").value = total;

function CallAllMeth() {
    findTotalAchat();
    findTotalVenteEnGros();
    findTotalVenteEnDetail();
    beneficeEnGros();
    CalQteDetail();
    totalEnDetail();

    CalculateBenefice(
        "total_achat",
        "Total_en_gros",
        "Total_benefice_en_gros",
        "total_achat",
        "Total_en_detail",
        "Total_benefice_en_detail"
    );
    totalQteEnDetail("qte_achat", "qte_par_carton");
}

$("#btnreduction").click(function () {
    var montantApayer = parseInt(
        document.getElementById("montantApayer").value
    );
    var reduction = parseInt(document.getElementById("reduction").value);
    var btnreduction = document.getElementById("btnreduction");
    var reduct = document.getElementById("reduction");
    var total_ttc = parseInt(document.getElementById("total_ttc").value);
    var total_tva = parseInt(document.getElementById("total_tva").value);

    if (
        typeof montantApayer === "number" &&
        typeof reduction === "number" &&
        !isNaN(montantApayer) &&
        !isNaN(reduction)
    ) {
        if (total_tva != "0.05" || total_tva != "0.18") {
            document.getElementById("montantApayer").value =
                montantApayer - reduction;
        } else {
            document.getElementById("total_ttc").value = total_ttc - reduction;
        }

        var red = document.getElementById("reduction");
        red.style.borderColor = "green";
        red.style.borderWidth = "3px";
        red.style.fontWeigth = "bold";
        //red.setAttribute("readonly");
        document.getElementById("ok").innerHTML =
            "La réduction est appliquée !";
        btnreduction.style.display = "none";
        CalculateBalanceBeforePay();
    }
});

//Get all students information
jQuery('select[name="product"]').on("change", function () {
    var studentId = jQuery(this).val();
    if (studentId) {
        jQuery.ajax({
            url: "/fatchstudentInfo/" + studentId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                jQuery.each(data, function (key, value) {
                    $("#nom_produit").val(value.nom_produit);
                    $("#prix").val(value.prix_vente_unitaire);
                    $("#id_prod").val(value.id);
                    $("#prod").val(value.id_prod);
                    $("#categorie").val(value.id_categorie);
                    $("#code_barre").val(value.code_barre);
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr);
            },
        });
    } else {
        $("#nom_produit").val("");
        $("#prix").val("");
        $("#quantite").val("");
        $("#total").val("");
        $("#id_prod").val("");
        $("#prod").val("");
    }
});

$(document).ready(function () {
    $("#barcode-form").submit(function (event) {
        event.preventDefault(); // prevent form from submitting normally
        //alert("Test");

        var barcode = $("#barcode-input").val();
        // send AJAX request to server-side script
        if (barcode) {
            jQuery.ajax({
                url: "/fatchstudentInfo/" + barcode,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    jQuery.each(data, function (key, value) {
                        $("#nom_produit").val(value.nom_produit);
                        $("#prix").val(value.prix_vente_unitaire);
                        $("#id_prod").val(value.id);
                        $("#categorie").val(value.id_categorie);
                        $("#code_barre").val(value.code_barre);
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                },
            });
        } else {
            $("#nom_produit").val("");
            $("#prix").val("");
            $("#quantite").val("");
            $("#total").val("");
            $("#id_prod").val("");
        }
    });
});

//Get all students information
jQuery('input[name="code_barre"]').on("change", function () {
    var studentId = jQuery(this).val();
    if (studentId) {
        jQuery.ajax({
            url: "/fatchstudentInfo/" + studentId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                jQuery.each(data, function (key, value) {
                    $("#nom_produit").val(value.nom_produit);
                    $("#prix").val(value.prix_vente_unitaire);
                    $("#id_prod").val(value.id);
                    $("#prod").val(value.id_prod);
                    $("#categorie").val(value.id_categorie);
                    $("#code_barre").val(value.code_barre);
                });
            },
            error: function (xhr, status, error) {
                console.error(xhr);
            },
        });
    } else {
        $("#nom_produit").val("");
        $("#prix").val("");
        $("#quantite").val("");
        $("#total").val("");
        $("#id_prod").val("");
        $("#prod").val("");
    }
});

//Get all students information
// jQuery('select[name="options"]').on("change", function () {
//     var studentId = jQuery(this).val();
//     var id_prod = jQuery("#id_prod").val();
//     if (studentId) {
//         jQuery.ajax({
//             url: "/fatchstudentInfo/" + id_prod,
//             type: "GET",
//             dataType: "json",
//             success: function (data) {
//                 jQuery.each(data, function (key, value) {
//                     if (studentId == "1") {
//                         $("#prix").val(value.prix_vente_unitaire);
//                     } else if (studentId == "2") {
//                         $("#prix").val(value.prix_vente_en_gros);
//                     }
//                 });
//             },
//             error: function (xhr, status, error) {
//                 console.error(xhr);
//             },
//         });
//     } else {
//         $("#quantite").val("");
//         $("#prix").val("");
//         $("#total").val("");
//     }
// });

// Show the modal of panier boutique
$(document).ready(function () {
    // Pour la vente
    $(".panier").click(function () {
        var panieId = $(this).data("id");
        if (panieId) {
            jQuery.ajax({
                url: `/fatche_produit/${panieId}/info`,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    $("#product").val(data.libelle);
                    $("#nom_produit").val(data.libelle);
                    $("#prix").val(data.prix_vente_unitaire);
                    $("#prix_detail").val(data.prix_vente_unitaire);
                    $("#prix_en_gros").val(data.prix_vente_en_gros);
                    $("#id_prod").val(data.id_prod);
                    $('#stock_id').val(data.stock_id);
                    $("#categorie").val(data.nom_categorie);
                    $("#id_categorie").val(data.id_categorie);
                    $("#product_id").val(data.id);
                    $("#code_barre").val(data.code_barre);
                    $("#Ajoutpanier").modal("show");
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                },
            });
        } else {
            $("#nom_produit").val("");
            $("#prixU").val("");
            $("#qtite").val("");
            $("#totalV").val("");
            $("#id_prod").val("");
            $("#Ajoutpanier").modal("hidden");
        }
    });

    // Pour la dette
    $(".dette_panier").click(function () {
        var panieId = $(this).data("id");
        if (panieId) {
            jQuery.ajax({
                url: `/fatche_produit/${panieId}/info`,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $("#nom_produit").val(data.libelle);
                    $("#prix").val(data.prix_vente_unitaire);
                    $("#prix_detail").val(data.prix_vente_unitaire);
                    $("#prix_en_gros").val(data.prix_vente_en_gros);
                    $("#id_prod").val(data.id_prod);
                    $('#stock_id').val(data.stock_id);
                    $("#categorie").val(data.nom_categorie);
                    $("#id_categorie").val(data.id_categorie);
                    $("#product_id").val(data.id);
                    $("#code_barre").val(data.code_barre);
                    $("#add_panier").modal("show");
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                },
            });
        } else {
            $("#nom_produit").val("");
            $("#prixU").val("");
            $("#qtite").val("");
            $("#totalV").val("");
            $("#id_prod").val("");
            $("#add_panier").modal("hidden");
        }
    });
});

function totalAchats() {
    var qte_total_en_detail = parseFloat(
        document.getElementById("quantity").value
    );
    var prix_vente_unitaire = parseFloat(document.getElementById("prix").value);
    if (
        typeof prix_vente_unitaire === "number" &&
        typeof qte_total_en_detail === "number" &&
        !isNaN(qte_total_en_detail) &&
        !isNaN(prix_vente_unitaire)
    ) {
        document.getElementById("total").value =
            prix_vente_unitaire * qte_total_en_detail;
    }
}
// Change price of product
jQuery('select[name="options"]').on("change", function () {
    let option = jQuery(this).val();
    let prix_1 = parseFloat(document.getElementById("prix_detail").value) || 0;
    let prix_2 = parseFloat(document.getElementById("prix_en_gros").value) || 0;
    if (option == "1") {
        $("#prix").val(prix_1);
    } else if (option == "2") {
        $("#prix").val(prix_2);
    }
    totalInTable();
});

function totalInTable() {
    var priceInput = document.getElementById("prix");
    var quantityInput = document.getElementById("quantite");
    var amountInput = document.getElementById("total");

    var price = parseFloat(priceInput.value) || 0;
    var quantity = parseFloat(quantityInput.value) || 0;
    var amount = price * quantity;

    amountInput.value = amount;
}

// function totalInTable() {
//     var rows = document.querySelectorAll("#example3 tbody tr");

//     rows.forEach(function (row) {
//         var priceInput = row.querySelector(".prix");
//         var quantityInput = row.querySelector(".quantite");
//         var amountInput = row.querySelector(".total");

//         var price = parseFloat(priceInput.value);
//         var quantity = parseFloat(quantityInput.value);
//         var amount = price * quantity;

//         amountInput.value = amount;
//     });
// }
