const regex = /((?:(?:[123]|I{1,3})\s*)?(?:[A-Z][A-Za-zá-ùÁ-Ù]+|Ct|Cc).?\s*(?:1?[0-9]?[0-9])(:|\.)\s*\d{1,3}(?:[,-]\s*\d{1,3})*(?:;\s*(?:(?:[123]|I{1,3})\s*)?(?:[A-Z][A-Za-zá-ùÁ-Ù]+|Ct|Cc)?.?\s*(?:1?[0-9]?[0-9]):\s*\d{1,3}(?:[,-]\s*\d{1,3})*)*)/gim

async function getVerses(ref) {
    const url = `https://bible-api.com/${ref}?translation=almeida`
    const response = await fetch(url)
    if (!response.ok) {
        throw new Error(`Um erro ocorreu: ${response.status}`);
    }
    const json = await response.json()
    const verses = json
    console.log(verses.text);
    return verses
}

const abbrs = Array.prototype.slice.apply(document.querySelectorAll("abbr[rel=tooltip]"))
abbrs.forEach(function (tip) {
    tip.addEventListener('mouseover', function () {
        const ref = this.dataset.ref
        getVerses(ref)
    })
})