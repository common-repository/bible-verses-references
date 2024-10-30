document.addEventListener("DOMContentLoaded", function () {
    const targets = document.querySelectorAll("[rel~=tooltip]");
    let target = false,
        tooltip = false,
        title = false,
        timeout;

    async function getVerses(ref) {
        ref = ref.replaceAll(";", ",");
        let url = `https://bible-api.com/${ref}`;
        if (bible_verses_refs.translation) {
            url += `?translation=${bible_verses_refs.translation}`;
        }
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Um erro ocorreu: ${response.status}`);
        }
        const json = await response.json();
        const verses = json.verses.reduce((acc, verse) => {
            acc += `<sup>(${verse.chapter}.${verse.verse}) </sup>${verse.text}`;
            return acc;
        }, "");

        return { verses, translation: json.translation_name };
    }

    targets.forEach(function (citeTag) {
        citeTag.addEventListener("mouseenter", async function () {
            target = this;

            if (target.dataset.status == "ok") return;

            let tip = "";
            let ref = target.textContent;
            if (!target.dataset.ref) {
                target.dataset.ref = ref;
            }

            if (!target.dataset.status) {
                tooltip = document.createElement("div");
                tooltip.id = "tooltip";
                tooltip.className = "rtTooltip rtLight rtTooltipDropShadow";
                container = document.createElement("div");
                container.className = "rtContainer";
                tooltipheader = document.createElement("div");
                tooltipheader.className = "rtTooltipHeader";
                tooltipbody = document.createElement("div");
                tooltipbody.className = "rtTooltipBody";
                tooltipfooter = document.createElement("div");
                tooltipfooter.className = "rtTooltipFooter";

                container.appendChild(tooltipheader);
                container.appendChild(tooltipbody);
                container.appendChild(tooltipfooter);
                tooltip.appendChild(container);

                tooltipheader.innerHTML = target.dataset.ref;
                tooltipbody.innerHTML = `${bible_verses_refs.await}`;
                target.appendChild(tooltip);

                init_tooltip();

                target.dataset.status = "load";
            }

            if (target.dataset.status == "load") {
                // Configura um timeout para 2 segundos
                timeout = setTimeout(async function () {
                    tip = await getVerses(target.dataset.ref);
                    tooltipbody.innerHTML = tip.verses;
                    tooltipfooter.innerHTML = tip.translation;
                    target.dataset.translation = tip.translation;
                    target.dataset.verses = tip.verses;
                    target.dataset.status = "ok";
                    init_tooltip();
                }, 1);
            } else {
                tooltipbody.innerHTML = title;
                init_tooltip();
            }

            function init_tooltip() {
                if (window.innerWidth < tooltip.offsetWidth * 1.5) {
                    tooltip.style.maxWidth = window.innerWidth / 2 + "px";
                } else {
                    tooltip.style.maxWidth = "340px";
                }

                let pos_left =
                    target.offsetLeft +
                    target.offsetWidth / 2 -
                    tooltip.offsetWidth / 2;
                let pos_top =
                    target.offsetTop -
                    document.documentElement.scrollTop -
                    tooltip.offsetHeight -
                    20;

                if (pos_left < 0) {
                    pos_left = target.offsetLeft + target.offsetWidth / 2 - 20;
                    tooltip.classList.add("left");
                } else {
                    tooltip.classList.remove("left");
                }

                if (pos_left + tooltip.offsetWidth > window.innerWidth) {
                    pos_left =
                        target.offsetLeft -
                        tooltip.offsetWidth +
                        target.offsetWidth / 2 +
                        20;
                    tooltip.classList.add("right");
                } else {
                    tooltip.classList.remove("right");
                }

                if (pos_top < 0) {
                    pos_top = target.offsetTop + target.offsetHeight;
                    tooltip.classList.add("top");
                } else {
                    pos_top = target.offsetTop - tooltip.offsetHeight - 2;
                    tooltip.classList.remove("top");
                }

                tooltip.style.left = pos_left + "px";
                tooltip.style.top = pos_top + "px";
                tooltip.style.transition = "top 0.05s, opacity 0.05s";
                tooltip.style.opacity = 1;
            }

            const remove_tooltip = function (event) {
                clearTimeout(timeout);
            };

            window.addEventListener("resize", init_tooltip);
            target.addEventListener("mouseout", remove_tooltip);
        });
    });
});
