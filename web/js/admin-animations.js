$(function () {
  console.log("admin-animations loaded ✅");

  // Cibles robustes (au cas où .table n'est pas utilisée)
  const $blocks = $(".adminTable, .tab-content, .panel, .well");
  const $rows = $(".adminTable table tbody tr, table.table tbody tr");

  // 1) Apparition douce (on initialise via JS -> pas besoin d'opacité 0 en CSS)
  $blocks.css({ opacity: 0, transform: "translateY(12px)" });

  $blocks.each(function (i) {
    const $el = $(this);
    setTimeout(function () {
      $el.animate({ opacity: 1 }, 220);
      $el.css({ transform: "translateY(0)" });
    }, 120 + i * 120);
  });

  // 2) Hover léger sur lignes (sur n'importe quel tableau dans l'admin)
  $rows.hover(
    function () {
      $(this).css("transform", "translateY(-2px)");
    },
    function () {
      $(this).css("transform", "translateY(0)");
    }
  );

  // 3) Transition lors du changement d'onglet (Bootstrap tab event)
  $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    const targetSelector = $(e.target).attr("href");
    const $target = $(targetSelector);

    $target.css({ opacity: 0, transform: "translateY(10px)" });
    $target.animate({ opacity: 1 }, 200);
    $target.css({ transform: "translateY(0)" });
  });
});
