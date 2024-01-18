const formatCurrency = (value) => {
    if (!value) return "";
    const val = String(value);
    return val
        .split(".")
        .join("")
        .split("")
        .reverse()
        .join("")
        .match(/.{1,3}/g)
        .join(".")
        .split("")
        .reverse()
        .join("");
};

export default formatCurrency;
