<template>
  <div class="input-group mb-3">
    <input
    class="form-control"
      type="date"
      @input="(event)=>handleDate(event.target.value)"
      :min="formattedMinDate"
      :max="formattedMaxDate"
      :style="{color:data.dateColor}"
    />
    <div class="input-group-append">
      <input
        type="time"
        class="form-control"
        @input="(event)=>handleTime(event.target.value)"
        :min="formattedMinDate"
        :max="formattedMaxDate"
        :style="{color:data.dateColor}"
      />
    </div>
  </div>
</template>

<script>
import { InputMixin } from "./mixin/InputMixin.js";
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
  name: "DatePicker",
  extends: CoreLibrary,

  mixins: [InputMixin],

  data() {
    return {
      borderGroup: false,
      date: '', // 12 or 24 hour format
      time:''
    };
  },
  computed: {
    isRange() {
      if (this.data.dateMode === "range") {
        return true;
      }
      return this.isUndefined(this.data.isRange) ? false : this.data.isRange;
    },
    dateMode() {
      if (
        this.data.dateMode === "range" ||
        this.isUndefined(this.data.dateMode)
      ) {
        return "date";
      } else return this.data.dateMode;
    },
    formattedMinDate() {
      return this.formatDate(this.data.minDate);
    },
    formattedMaxDate() {
      return this.formatDate(this.data.maxDate);
    },
  },
  methods: {
    input(date) {
      // console.log(date)
      this.fieldValue = date;
      this.$emit("input", date);
    },
    formatDate(date) {
      // Convert Date object or date string to YYYY-MM-DD
      if (typeof date === 'string' || date instanceof Date) {
        const d = new Date(date);
        return d.toISOString().split('T')[0]; // Convert to YYYY-MM-DD
      }
      return date;
    },

    handleDate(value){
      this.date=value
      const dateTime = `${this.date} ${this.time}`
      console.log('from the date change', dateTime);
      this.input(dateTime);
      // this.$emit('change', value);
      // this.$emit('input', value);
    },
    handleTime(value){
      this.time=value+':00'
      const dateTime = `${this.date} ${this.time}`
      console.log('from the time change', dateTime);
      this.input(dateTime);
      // this.$emit('change', value);
      // this.$emit('input', value);
    }
  },
};
</script>
