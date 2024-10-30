const abbrs = Array.prototype.slice.apply(document.querySelectorAll("abbr[rel=tooltip]"))

abbrs.forEach(function (tip) {
    tip.addEventListener('click', function () {
        func(this)
        console.log('teste');
    })
})

function func(el) {
    //const el = event.target;
    const title = el.getAttribute('title');
    const reference = el.textContent

    const tooltip = document.createElement("div");
    tooltip.setAttribute("id", "tooltip")
    tooltip.classList.add("rtTooltip", "rtLight", "rtTooltipDropShadow")

    const container = document.createElement("div");
    container.classList.add("rtContainer")

    const tooltipheader = document.createElement("div")
    tooltipheader.classList.add("rtTooltipHeader")
    tooltipheader.textContent = reference

    const tooltipbody = document.createElement("div")
    tooltipbody.classList.add("rtTooltipBody")
    tooltipbody.textContent = title

    const tooltipfooter = document.createElement("div")
    tooltipfooter.classList.add("rtTooltipFooter")
    tooltipfooter.textContent = "Footer"

    container.appendChild(tooltipheader)
    container.appendChild(tooltipbody)
    container.appendChild(tooltipfooter)

    tooltip.appendChild(container)

    const corpo = document.querySelector("body");
    corpo.appendChild(tooltip);
    tooltip.style.opacity = 0;
    el.removeAttribute("title");

    // função iniciar
    const init_tooltip = function () {
        if (window.innerWidth < tooltip.offsetWidth * 1.5) {
            tooltip.style.maxWidth = (window.innerWidth / 2) + "px";
        } else {
            tooltip.style.maxWidth = "340px";
        }

        let pos_left = getOffset(el).left + (el.offsetWidth / 2) - (tooltip.offsetWidth / 2);
        let pos_top = getOffset(el).top - document.documentElement.scrollTop - tooltip.offsetHeight - 20;
        // let pos_left = getPosition(el).x + (el.offsetWidth / 2) - (tooltip.offsetWidth / 2);
        // let pos_top = getPosition(el).y - window.pageYOffset - tooltip.offsetHeight - 20;

        if (pos_left < 0) {
            pos_left = el.offsetLeft + el.offsetWidth / 2 - 20;
            tooltip.classList.add('left');
        } else {
            tooltip.classList.remove('left');
        }

        if (pos_left + tooltip.offsetWidth > window.innerWidth) {
            pos_left = el.offsetLeft - tooltip.offsetWidth + el.offsetWidth / 2 + 20;
            tooltip.classList.add('right');
        } else {
            tooltip.classList.remove('right');
        }

        if (pos_top < 0) {
            pos_top = el.offsetTop + el.offsetHeight + 10;
            tooltip.classList.add('top');
        } else {
            pos_top = el.offsetTop - tooltip.offsetHeight - 10;
            tooltip.classList.remove('top');
        }

        tooltip.style.left = pos_left + "px";
        tooltip.style.top = pos_top + "px";
        tooltip.style.opacity = 1;
        tooltip.style.transition = "all 0.5s";

    };

    init_tooltip();

    function esconder() {

        if (tooltip.parentNode) {
            corpo.removeChild(tooltip);
        }

        el.setAttribute("title", title);
    };

    el.addEventListener("blur", esconder);

    el.addEventListener("mouseout", esconder);
}


function getOffset(el) {
    var _x = 0;
    var _y = 0;
    while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return {
        top: _y,
        left: _x
    };
}




