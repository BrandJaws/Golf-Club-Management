@include("admin.__vue_components.reservations.reservation-tab-heads")
<script>

Vue.component('reservation-tabs', {
    template: `
              		<div :class="styleForShowMoreTabData ? ['tsheet-tabs-reser'] : ['tsheet-tabs', 'padd-15']">
                                <slot></slot>
                         </div>
          
            `,
    props: [
            "reservationsParent",
            "styleForShowMoreTab"
            
            
    ],
    data: function () {
       
      return {
          reservationsByDate:this.reservationsParent.reservationsByDate,
          styleForShowMoreTabData:this.styleForShowMoreTab != null && this.styleForShowMoreTab.toLowerCase()== 'true' ? true : false,
      }
    }
  
});
</script>
