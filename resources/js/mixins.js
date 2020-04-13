let blogMixins = {
    filterTable(values, filterBy = []) {
        return values.filter((row) => {
            let validValue = true
            Object.keys(filterBy).forEach(key => {
                if (filterBy[key].length || typeof filterBy[key] === "number") {
                    if (Array.isArray(filterBy[key])) {
                        if (!filterBy[key].includes(row[key]) && !filterBy[key].includes(row[key + '_id'])) {
                            validValue = false
                        }
                    }
                    else if (isNaN(filterBy[key]) || key === 'name' || key === 'title') {
                        if (key === 'from-created_at') {
                            if (Date.parse(row['created_at']) < Date.parse(filterBy[key])) {
                                validValue = false
                            }
                        } else if (key === 'to-created_at') {
                            let endDate = new Date(filterBy[key]);
                            endDate.setDate(endDate.getDate() + 1);
                            if (new Date(row['created_at']) > endDate) {
                                validValue = false
                            }
                        }
                        else if (((row[key]).toLowerCase()).indexOf(filterBy[key].toLowerCase()) === -1) {
                            validValue = false
                        }
                    }
                    else if (!isNaN(filterBy[key])) {
                        if (parseInt(row[key]) !== parseInt(filterBy[key])) {
                            validValue = false
                        }
                    }
                }
            })

            return validValue
        })
    },
    normalize(string) {
        return string.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")
    },
    orderTable(values, orderBy = [], tableFields = []) {
        orderBy.forEach(fieldIndex => {
            let field = tableFields[Math.abs(fieldIndex) - 1]
            if (field === 'date') {
                field = 'created_at'
            }
            let reverse = fieldIndex < 0 ? 1 : -1
            values.sort((a, b) => {
                if (isNaN(a[field]) && isNaN(b[field])) {
                    if (a[field].text) {
                        return this.normalize(a[field].text) > this.normalize(b[field].text) ? 1 * reverse : -1 * reverse
                    }
                    else {
                        return this.normalize(a[field]) > this.normalize(b[field]) ? 1 * reverse : -1 * reverse
                    }
                } else {
                    return a[field] > b[field] ? 1 * reverse : -1 * reverse
                }
            })
        })

        return values
    },
    prepareTable(values, tableFields = [], orderBy = [], filterBy = []) {
        return this.orderTable(this.filterTable(values, filterBy), orderBy, tableFields)
    },
    trans: function (string, language = null) {
        if (!language) language = this.$store.getters.currentLanguage;
        let trans = lang.get(string, null, language);

        if (trans === string) {
            trans = lang.get(string, null, this.$store.getters.defaultLanguage);
        }

        return trans;
    },
}

export default blogMixins