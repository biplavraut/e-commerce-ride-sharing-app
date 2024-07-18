export const index = {
    methods: {
        async getModels() {
            this.rows = await this.model.getPaginatedList();
        }
    }
};

export const destroy = {
    methods: {
        async deleteModel(id) {
            if (this.authUser.type == "admin" || this.authUser.type == "superadmin") {
                if (confirm("Are you sure? You will not be able to recover your data in the future.")) {
                    await this.model.delete(id);
                    alertMessage('Data successfully deleted.');
                    this.rows.data = this.rows.data.filter(item => item.id !== id);
                    this.model.cache.updateList(this.rows);
                    /*this.$store.commit('updateThisMonthCounts', {
                      type : this.model.namePlural,
                      count: -1
                    });*/
                }
            } else {
                alertMessage('Unauthorized Access! Contact Your Admin.', "warning")
            }

        }
    }
};

export const destroyMultiple = {
    methods: {
        async deleteMultiple(ids) {
            if (confirm("Are you sure? You will not be able to recover your data in the future.")) {
                let numOfRows = await this.model.deleteMultiple({ ids });

                alertMessage(numOfRows + ' row/s of data deleted.');
                this.rows.data = this.rows.data.filter(item => !ids.includes(item.id));

                /*this.$store.commit('updateThisMonthCounts', {
                  type : this.model.namePlural,
                  count: -numOfRows
                });*/
            }
        }
    }
};

export const store = {
    methods: {
        async storeData() {
            this.form.errors.clear();
            try {
                await this.model.store(this.form.data());
                alertMessage("Data saved successfully.");
                this.model.cache.invalidate();
                /*this.$store.commit('updateThisMonthCounts', {
                  type : this.model.namePlural,
                  count: 1
                });*/
                this.$router.push({ name: `${this.model.nameLowerCase}.index` });
            } catch (error) {
                this.form.errors.initialize(error.data.errors);
                if (this.form.errors.has("image")) Helpers.focusId("image");
                if (this.form.errors.has("icon")) Helpers.focusId("icon");
            }
        }
    }
};

export const save = {
    methods: {
        saveData() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.edit ? this.updateData() : this.storeData();
                } else {
                    Helpers.focusFirstError(this.errors);
                }
            });
        }
    }
};