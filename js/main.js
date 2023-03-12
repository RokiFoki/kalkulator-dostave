jQuery.browser = {};
(function () {
  jQuery.browser.msie = false;
  jQuery.browser.version = 0;
  if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
    jQuery.browser.msie = true;
    jQuery.browser.version = RegExp.$1;
  }
})();
var posiljka = null,
  cijena = null,
  prevPos = null,
  basket = new Basket(),
  currentItem = basket.addItem(new Item([], null, "", "", "", 2), 2),
  selectedOd = "",
  selectedDo = "",
  ukupno = null,
  firstOpen = true,
  firstPopulated = true,
  selectedTarifa = 1,
  lastFocused = "kutija",
  delay;

$(document).ready(function () {
  $(".item").append('<div class="close"></div>');

  setInterval(function () {
    checkForChange();
  }, 300);

  if ($(window).width() >= 481) {
    $(".tooltip").tooltipster({
      tooltipTheme: ".tooltipster-clean-cut",
      position: "top",
    });
  }

  $("select").chosen();

  $("#kalkulator-content").on("click", ".izbor", function (e) {
    activeEle = $(".dim")[0];
    if ($(this).attr("id") === "moto") {
      lastFocused = "marker";
      $("#mali-sim-tam").removeClass("kutija").addClass("marker");
    } else {
      lastFocused = "kutija";
      $("#mali-sim-tam").removeClass("marker").addClass("kutija");
    }
    selectIzbor($(this));
  });

  $("#kalkulator-content").on(
    "click mouseenter mouseleave",
    ".item",
    function (e) {
      var justOne = basket.justOneLeft();
      var that = $(this);
      var thatClose = $(that.find(".close"));
      var isDisabled = that.hasClass("disabled");

      if (e.type === "mouseenter") {
        if (isDisabled) {
          that.append('<div class="dodaj">DODAJ POŠILJKU</div>');
        }
        if (!isDisabled && !justOne) {
          thatClose.show();
        }
      }
      if (e.type === "mouseleave") {
        if (!isDisabled) {
          thatClose.hide();
        }
        $(".dodaj").remove(".dodaj");
      }
      if (e.type === "click") {
        if ($(e.target).hasClass("close") && !justOne) {
          var index = $(e.target).parent().index();
          closeItem(index, e);
        } else {
          if (!justOne) {
            thatClose.show();
          }
          basketHandler(that);
          $(".dodaj").remove(".dodaj");
        }
      }
    }
  );

  $("#kalkulator-content").on("change", ".change", function () {
    var isMoto = posiljka === "moto";
    if (firstPopulated) {
      if (currentItem.populate()) {
        if (isMoto || currentItem.validate()) {
          basket.showItemPrice(displayVals(currentItem));
          firstPopulated = isMoto || false;
        }
      }
    } else {
      currentItem.populate();
      if (currentItem.validate()) {
        basket.showItemPrice(displayVals(currentItem));
      }
    }
    basket.showTotal();
  });

  $("#kalkulator-content").on("keydown", ".change", function () {
    clearTimeout(delay);
    delay = setTimeout(function () {
      $("#mass").trigger("change");
    }, 300);
  });

  $("input").on(
    "focus",
    $.debounce(300, function (e) {
      var focusedParent = $($(this).parent()),
        maliSimTam = $("#mali-sim-tam"),
        focused;
      if (
        (focusedParent.hasClass("dimenzije") ||
          focusedParent.hasClass("masa")) &&
        posiljka !== "moto"
      ) {
        focused = "kutija";
      } else {
        console.log("hura");
        focused = "marker";
      }

      if (focused !== lastFocused) {
        maliSimTam.removeClass(lastFocused);
        maliSimTam.addClass(focused);
        lastFocused = focused;
      }
    })
  );
});

function useOldValues() {
  return $("section[oldvalues]").attr("oldvalues") === "true";
}

function displayVals(item) {
  var odLabel = getOptgroupLabel("od"),
    doLabel = getOptgroupLabel("do"),
    zones = [odLabel, doLabel],
    gabarit = false,
    cijena = null,
    cijenaHitno = null,
    unutar,
    unutarHitno = null,
    prevVehicle = "",
    mass = item.mass,
    sortedDim = item.getSortedDim(),
    hitno = {
      moto: [26, 60],
      caddy: [39, 90],
    },
    vozilo1 = {
      moto: 0,
      caddy: 1,
      kombi: 2,
    },
    vremena = [
      // vozilo x zona
      [120, 150, 180, 210],
      [150, 180, 210, 240],
      [240, 240, 240, 240],
    ],
    odVal,
    doVal;

  if (posiljka === "moto") {
    sortedDim = [1, 1, 1];
    mass = 1;
  }

  if (!useOldValues()) {
    odVal = $("#od").val().split(";")[0];
    doVal = $("#do").val().split(";")[0];
  } else {
    odVal = $("#od").val().split(";")[1];
    doVal = $("#do").val().split(";")[1];
  }

  disallwoMotoOd = isNaN(parseFloat(odVal.split(",")[vozilo1["moto"]]));
  disallwoCaddyOd = isNaN(parseFloat(odVal.split(",")[vozilo1["caddy"]]));

  disallwoMotoDo = isNaN(parseFloat(doVal.split(",")[vozilo1["moto"]]));
  disallwoCaddyDo = isNaN(parseFloat(doVal.split(",")[vozilo1["caddy"]]));

  vehicle = getVehicle(
    sortedDim,
    mass,
    zones,
    disallwoMotoOd || disallwoMotoDo,
    disallwoCaddyOd || disallwoCaddyDo
  );
  unutar = Math.max(
    vremena[vozilo1[vehicle]][odLabel - 1],
    vremena[vozilo1[vehicle]][doLabel - 1]
  );
  odVal = parseFloat(odVal.split(",")[vozilo1[vehicle]]);
  doVal = parseFloat(doVal.split(",")[vozilo1[vehicle]]);
  base = setBase(vehicle);

  var priceAdd = parseFloat(getPrice(sortedDim, mass, zones)[1]);

  if (zones[0] !== zones[1]) {
    cijena = base + odVal + doVal + priceAdd;
  } else {
    cijena = base + odVal + doVal + priceAdd;
    if (zones[0] == 2) {
      strana_svijeta_od = $("#od").val().split(";")[2];
      strana_svijeta_do = $("#do").val().split(";")[2];

      if (strana_svijeta_od != strana_svijeta_do) {
        // cijene za drugu zonu se povecaju za deset ako nisu u istom dijelu grada.
        cijena += 10;

        // vijeme se isto povecava za 30
        unutar += 30;
      }
    }
  }

  if (doLabel < 1 && odLabel < 1 && vehicle !== "kombi") {
    cijenaHitno = cijena + hitno[vehicle][0];
    unutarHitno =
      Math.max(
        vremena[vozilo1[vehicle]][odLabel - 1],
        vremena[vozilo1[vehicle]][doLabel - 1]
      ) - 30;
  }

  showResVehicle(vehicle, prevVehicle);
  prevVehicle = vehicle;

  currentItem.cijena = [cijena, cijenaHitno];
  return {
    price: cijena,
    priceUrgent: cijenaHitno,
    timeFrame: unutar,
    timeFrameUrgent: unutarHitno,
  };
}

function showResVehicle(vehicle) {
  var el = $($(".item")[currentItem.UID]);
  var image = el.find(".rez-image");
  var opis = el.find(".rezvoz-opis");
  var premiumText = "";
  var opisClass = "crveno";
  if (currentItem.selectedCijena === 1) {
    opisClass = "premium";
    premiumText = "<br><span>PREMIUM</span>";
  }
  image.removeClass("rezvoz-caddy rezvoz-moto rezvoz-kombi");
  opis.text("");
  if (vehicle !== "") {
    image.addClass("rezvoz-" + vehicle);
    opis.html(
      'Naručite uslugu<span class="' +
        opisClass +
        '">SIM-TAM ' +
        vehicle.toUpperCase() +
        premiumText +
        "</span>"
    );
    $("#rezvoz").removeClass("visuallyhidden");
  }
}

function selectIzbor(that) {
  prevPos = posiljka;
  $(".izbor").each(function () {
    $(this).removeClass("selected");
    $(this).addClass("unselected");
  });
  that.addClass("selected");
  that.removeClass("unselected");
  posiljka = that.attr("id");

  if (posiljka === "moto") {
    activeEle = $("#od_chzn .chzn-single");
    $(".dimenzije").hide();
    $("#mali-sim-tam").addClass("alt-sim-tam");
    $(".masa").hide(0, slideScroll);
  } else {
    $(".dimenzije").show();
    $("#mali-sim-tam").removeClass("alt-sim-tam");
    $(".masa").show(0, slideScroll);
  }
  if (prevPos !== posiljka) {
    clearAll();
  }
  that.trigger("change");
}

function fillForm(item) {
  var i;
  if (item.shipType !== "moto") {
    for (i = item.dim.length - 1; i >= 0; i--) {
      $($(".dim")[i]).val(item.dim[i]);
    }
    $($("#mass")).val(item.mass);
  }
  selectOpt("od", item.odText);
  selectOpt("do", item.doText);
  selectIzbor($("#" + item.shipType));
  selectTarifa($($(".kolko")[item.selectedCijena]));
}

function basketHandler(that) {
  $(".item").each(function () {
    $(this).removeClass("selected");
    $(this).addClass("unselected");
  });
  that.addClass("selected");
  that.removeClass("unselected");

  var index = that.index();
  if (typeof basket.items[index] === "undefined") {
    basket.addItem(_.extend(new Item(), currentItem), index);
    basket.items[index].UID = index;
    that.removeClass("disabled");
  }
  currentItem = basket.items[index];
  fillForm(currentItem);
}

function closeItem(index, e) {
  if (basket.items) {
    currentItem = basket.items[index];
    var el = $(e.target).parent().addClass("disabled");
    $(el.find(".close")).hide();
    showResVehicle("");
    basket.removeItem(index);
    currentItem = basket.items[getFirstDefinedIndex(basket.items)];
    var that = $($(".item")[getFirstDefinedIndex(basket.items)]);
    basketHandler(that);
  }
}

function selectTarifa(that) {
  $(".kolko").each(function () {
    $(this).removeClass("selected");
    $(this).addClass("unselected");
  });
  that.addClass("selected");
  that.removeClass("unselected");
}

/*
 *
 *---Utility Stuff----
 *
 */

function getOptgroupLabel(id) {
  var elt = $("#" + id)[0];
  var label = $(elt.options[elt.selectedIndex])
    .closest("optgroup")
    .prop("label");
  return parseInt(label.substr(0, 1), 10);
}

function getPrice(dim, mass, zones) {
  var calculatedPrice = 0,
    computedMass = mass,
    volume = getVolume(dim),
    volMass = Math.round(volume / 5555),
    priceClass = getPriceClass(dim, mass),
    vehicle = priceClass[0],
    vClass = priceClass[1],
    mClass = priceClass[2],
    theClass = 0,
    gabarit = false,
    classMultiplier = [0, 0, 4.9, 4.75, 4.6, 0, 4.9, 4.75],
    freeMass = {
      caddy: 20,
      kombi: 100,
    };

  if (volMass > mass) {
    computedMass = volMass;
  }

  if (vClass > mClass) {
    theClass = vClass;
  } else {
    theClass = mClass;
  }

  if (theClass !== 0 && theClass !== 1 && theClass !== 5) {
    calculatedPrice =
      (computedMass - freeMass[vehicle]) *
      0.01 *
      (classMultiplier[theClass] * 10);
  } else {
    calculatedPrice = 0;
  }
  return [vehicle, calculatedPrice.toFixed(2), gabarit];
}

function getPriceClass(dim, mass, zones) {
  var v = getVolume(dim),
    add = 0,
    massClass = 0,
    volumeClass = 0,
    priceClasses = {
      caddy: {
        volume: [250000, 500000, 1000000, 2000000],
        mass: [20, 60, 180, 600],
      },
      kombi: {
        volume: [1500000, 3000000, 9000000],
        mass: [100, 500, 1400],
      },
    };

  if (vehicle !== "moto") {
    add = vehicle === "caddy" ? 0 : 4;
    var pric = priceClasses[vehicle];
    for (var i = 0; i < pric.volume.length; i++) {
      if (v <= pric.volume[i]) {
        volumeClass = i + 1;
        break;
      }
    }

    for (var j = 0; i < pric.mass.length; j++) {
      if (mass <= pric.mass[j]) {
        massClass = j + 1;
        break;
      }
    }
    return [vehicle, volumeClass + add, massClass + add];
  } else {
    return [vehicle, 0, 0];
  }
}

function getVehicle(dim, mass, zones, disallowMoto, disallowCaddy) {
  var vehicles = {
    moto: [[15, 20, 30], 5, false],
    caddy: [[110, 110, 160], 600, false],
    kombi: [[150, 200, 370], 1400, false],
  };
  for (var prop in vehicles) {
    var vals = vehicles[prop];
    var d = vals[0];
    var m = vals[1];
    var diff = _.min(arraySubtr(d, dim));
    if (getVolume(dim) > getVolume(d) || mass > m) {
      vals[2] = false;
    } else {
      vals[2] = !(diff < 0);
    }
  }
  if (vehicles.moto[2] === true && _.max(zones) < 4 && !disallowMoto) {
    return "moto";
  } else {
    if (vehicles.caddy[2] === true && !disallowCaddy) {
      return "caddy";
    } else {
      if (vehicles.kombi[2] === true) {
        return "kombi";
      }
    }
  }
}

function setBaseOld(vozilo) {
  if (vozilo === "moto") {
    return 32;
  } else {
    if (vozilo === "caddy") {
      return 49;
    } else {
      if (vozilo === "kombi") {
        return 149;
      }
    }
  }
}

function setBase(vozilo) {
  if (useOldValues()) return setBaseOld(vozilo);

  if (vozilo === "moto") {
    return 50.9;
  } else {
    if (vozilo === "caddy") {
      return 73.9;
    } else {
      if (vozilo === "kombi") {
        return 149;
      }
    }
  }
}

function arraySubtr(arr1, arr2) {
  var tempArr = [3];
  for (var i = 0; i < 3; i++) {
    tempArr[i] = arr1[i] - arr2[i];
  }
  return tempArr;
}

function getVolume(dim) {
  return _.reduce(dim, function (memo, num) {
    return memo * num;
  });
}

function clearAll() {
  $("#cijena-2").css({
    display: "none",
  });
  $("#cijena-1 .cijena").text("--");
  $("#cijena-1 .tarifa").text("");
  if (basket.justOneLeft()) {
    $("#rezvoz").addClass("visuallyhidden");
  }
}

function isNormalInteger(str) {
  return /^([1-9]\d*)$/.test(str);
}

function validateNum(num) {
  if (num === null) {
    return false;
  } else if (isNaN(num)) {
    return false;
  } else {
    return true;
  }
}

function slideScroll() {
  if (firstOpen) {
    $(".od-do").slideDown(300);
    $.scrollTo(".kalkulator", 300, function () {
      $(activeEle).focus();
      $("#mali-sim-tam").fadeIn(1000).removeClass("hidden");
    });
  } else {
    $(activeEle).focus();
  }
  firstOpen = false;
}

function getSelectedOptText(id) {
  return $("#" + id + " option:selected")
    .text()
    .trim();
}

function selectOpt(id, optText) {
  var selectedEl = $("#" + id + ' option:contains("' + optText + '")');
  $("#" + id + " option").prop("selected", false);
  if (optText !== "") {
    selectedEl.prop("selected", true);
  }
  selectedEl.trigger("liszt:updated");
}

function getFirstDefinedIndex(arr) {
  var res = [];
  for (var i = arr.length - 1; i >= 0; i--) {
    if (typeof arr[i] !== "undefined") {
      res.push(i);
    }
  }
  return _.min(res);
}

function checkForChange() {
  if (
    selectedOd !== getSelectedOptText("od") ||
    selectedDo !== getSelectedOptText("do")
  ) {
    $("#mass").trigger("change");
  }
  selectedOd = getSelectedOptText("od");
  selectedDo = getSelectedOptText("do");
}
