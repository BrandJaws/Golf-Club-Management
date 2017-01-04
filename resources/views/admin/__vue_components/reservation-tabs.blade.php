@include("admin.__vue_components.reservation-tab-heads")
<script>

Vue.component('reservation-tabs', {
    template: `
              		<div :class="styleForShowMoreTab.toLowerCase() == 'true' ? ['tsheet-tabs-reser'] : ['tsheet-tabs', 'padd-15']">
                                <slot></slot>
                         </div>
          
            `,
    props: [
            "reservationsParent",
            "styleForShowMoreTab"
            
            
    ],
    data: function () {
       
      return {
          reservationsByDate:this.reservationsParent.reservationsByDate
      }
    }
  
});
</script>
