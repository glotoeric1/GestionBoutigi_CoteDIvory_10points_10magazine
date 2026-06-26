
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

//Get all students information
jQuery('#idvente').click(function () {
    //var studentId = jQuery(this).val();
    var id_prod = jQuery(".idcl").val();
    //console.log(id_prod);
    if (id_prod) {
        jQuery.ajax({
            url: '/orders/' + id_prod,
            type: "GET",
            dataType: "json",
            success: function (data) {
                jQuery.each(data, function (key, value) {
                    console.log('New ID : ' + value.clientId);

                });

            }, error: function (xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    else {
        $("#quantite").val('');
        $("#prix").val('');
        $("#total").val('');
    }
});


//get data 
function CalculateBenefice(idfirst, idSecond, idThird, idtotalAchat, idTotalVente, idBenefice) {

    var total_en_detail = parseInt(document.getElementById(idfirst).value);
    var Total_en_gros = parseInt(document.getElementById(idSecond).value);

    if (typeof Total_en_gros === "number" && typeof total_en_detail === "number" && !isNaN(Total_en_gros) && !isNaN(total_en_detail)) {
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
    if (typeof Total_en_gros === "number" && typeof total_en_detail === "number" && !isNaN(Total_en_gros) && !isNaN(total_en_detail)) {
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

    if (typeof prix === "number" && typeof qte === "number" && !isNaN(prix) && !isNaN(qte)) {
        document.getElementById('total_achat').value = prix * qte;
        //CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
        //totalQteEnDetail("qte_achat", "qte_par_carton")
    }

}

function findTotalVenteEnGros() {
    var prix = parseInt(document.getElementById("prix_vente_en_gros").value);
    var qte = parseInt(document.getElementById("qte_achat").value);
    if (typeof prix === "number" && typeof qte === "number" && !isNaN(prix) && !isNaN(qte)) {
        document.getElementById('Total_en_gros').value = prix * qte;
        //CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
    }
}


function findTotalVenteEnDetail() {
    var prix = parseInt(document.getElementById('prix_achat').value);
    var qte = parseInt(document.getElementById('qte_total_en_detail').value);
    if (typeof prix === "number" && typeof qte === "number") {
        document.getElementById('Total_en_detail').value = prix * qte;


        var total_en_detail = parseInt(document.getElementById('total_achat').value);
        var Total_en_gros = parseInt(document.getElementById('Total_en_gros').value);
        if (typeof Total_en_gros === "number" && typeof total_en_detail === "number") {
            document.getElementById('Total_benefice_en_gros').value = Total_en_gros - total_en_detail;
        }
        CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
    }
}

function beneficeEnGros() {
    var total_en_detail = parseInt(document.getElementById('Total_en_detail').value);
    var Total_en_gros = parseInt(document.getElementById('Total_en_gros').value);
    if (typeof Total_en_gros === "number" && typeof total_en_detail === "number") {
        //var somme   =Total_en_gros-total_en_detail; 
        document.getElementById('Total_benefice_en_gros').value = Total_en_gros - total_en_detail;
    }
}

function totalQteEnDetail(idQte1, idQte2) {
    //var qte_par_carton      = parseInt(document.getElementById('qte_par_carton').value);
    //var qte_achat           = parseInt(document.getElementById('qte_achat').value);

    var qte_par_carton = parseInt(document.getElementById(idQte1).value);
    var qte_achat = parseInt(document.getElementById(idQte2).value);
    if (typeof qte_achat === "number" && typeof qte_par_carton === "number") {
        var somme = qte_achat * qte_par_carton;
        document.getElementById('qte_total_en_detail').value = somme;
    }
}

function CalQteDetail() {
    totalQteEnDetail("qte_achat", "qte_par_carton");

}

function CalculateBalanceBeforePay() {
    var montantApayer = parseInt(document.getElementById("montantApayer").value);
    var montantDonner = parseInt(document.getElementById("montantDonner").value);

    if (typeof montantApayer === "number" && typeof montantDonner === "number" && !isNaN(montantApayer) && !isNaN(montantDonner)) {
        let benefice_en_gros = montantDonner - montantApayer;
        var totalGro = document.getElementById("restant");
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

function totalEnDetail() {

    var qte_total_en_detail = parseInt(document.getElementById("qte_total_en_detail").value);
    var prix_vente_unitaire = parseInt(document.getElementById("prix_vente_unitaire").value);
    if (typeof prix_vente_unitaire === "number" && typeof qte_total_en_detail === "number") {
        document.getElementById('Total_en_detail').value = prix_vente_unitaire * qte_total_en_detail;
    }
}

var priceCells = document.getElementsByClassName("totalTopay"); //returns a list with all the elements that have class 'priceCell'
var total = 0;
//loop over the cells array and add to total price 
for (var i = 0; i < priceCells.length; i++) {
    var thisPrice = parseFloat(priceCells[i].innerHTML); //get inner text of this cell in number format
    total = total + thisPrice;
};
//total = total.toFixed(2); //give 2 decimal points to total - prices are, e.g 59.80 not 59.8
document.getElementById("montantApayer").value = total;


function CallAllMeth() {
    findTotalAchat();
    findTotalVenteEnGros();
    findTotalVenteEnDetail();
    beneficeEnGros();
    CalQteDetail();
    totalEnDetail();

    CalculateBenefice('total_achat', 'Total_en_gros', 'Total_benefice_en_gros', 'total_achat', 'Total_en_detail', 'Total_benefice_en_detail');
    totalQteEnDetail("qte_achat", "qte_par_carton")
}

function reduction() {
    var montantApayer = parseInt(document.getElementById("montantApayer").value);
    var reduction = parseInt(document.getElementById("reduction").value);
    var btnreduction = document.getElementById("btnreduction");

    if (typeof montantApayer === "number" && typeof reduction === "number" && !isNaN(montantApayer) && !isNaN(reduction)) {
        document.getElementById("montantApayer").value = montantApayer - reduction;
        var red = document.getElementById("reduction");
        red.style.borderColor = "green";
        red.style.borderWidth = "3px";
        red.style.fontWeigth = "bold";
        //red.setAttribute("readonly");
        document.getElementById("ok").innerHTML = "La reduction est appliquer !";
        btnreduction.style.display = "none";
        CalculateBalanceBeforePay();

    }

}

//Get all students information
jQuery('select[name="product"]').on('change', function () {
    var studentId = jQuery(this).val();
    if (studentId) {
        jQuery.ajax({
            url: '/fatchstudentInfo/' + studentId,
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

            }, error: function (xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    else {
        $("#nom_produit").val('');
        $("#prix").val('');
        $("#quantite").val('');
        $("#total").val('');
        $("#id_prod").val('');
    }
});

//Get all students information
jQuery('input[name="code_barre"]').on('change', function () {
    var studentId = jQuery(this).val();
    if (studentId) {
        jQuery.ajax({
            url: '/fatchstudentInfo/' + studentId,
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

            }, error: function (xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    else {
        $("#nom_produit").val('');
        $("#prix").val('');
        $("#quantite").val('');
        $("#total").val('');
        $("#id_prod").val('');
    }
});


//Get all students information
jQuery('select[name="options"]').on('change', function () {
    var studentId = jQuery(this).val();
    var id_prod = jQuery("#id_prod").val();
    if (studentId) {
        jQuery.ajax({
            url: '/fatchstudentInfo/' + id_prod,
            type: "GET",
            dataType: "json",
            success: function (data) {
                jQuery.each(data, function (key, value) {
                    if (studentId == "1") {
                        $("#prix").val(value.prix_vente_unitaire);
                    } else if (studentId == "2") {
                        $("#prix").val(value.prix_vente_en_gros);
                    }

                });

            }, error: function (xhr, status, error) {
                console.error(xhr);
            }
        });
    }
    else {
        $("#quantite").val('');
        $("#prix").val('');
        $("#total").val('');
    }
});


function totalAchats() {

    var qte_total_en_detail = parseInt(document.getElementById("quantite").value);
    var prix_vente_unitaire = parseInt(document.getElementById("prix").value);
    if (typeof prix_vente_unitaire === "number" && typeof qte_total_en_detail === "number" && !isNaN(qte_total_en_detail) && !isNaN(prix_vente_unitaire)) {
        document.getElementById('total').value = prix_vente_unitaire * qte_total_en_detail;
    }
}
