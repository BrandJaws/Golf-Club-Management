<script>
       
      
        Vue.component('auto-complete-box', {
                template: `
                            <div style="width: 186px;" class="easy-autocomplete eac-plate-dark eac-description">
                                <input v-model="textFieldValue" placeholder="Type to add members" class="form-control autocomplete-input" type="text" @keydown.down="downArrowPressed" @keydown.up="upArrowPressed" @keydown.enter="enterKeyPressed" @keydown.esc="closeDropDownWithoutAction" @input="inputEvent" @blur="closeDropDownWithoutAction">
                                <div id="eac-container-eac-5104" class="easy-autocomplete-container" v-if="panelVisible" style="position:absolute;">
                                    <ul style="display: block;">
                                        <li v-for="(data,dataIndex) in dataListData" :class="[data.selected ? 'selected' : '','data-item']" @mouseover="mouseOver(dataIndex,data)" @mousedown="dataOptionClicked">
                                            <div class="eac-item" v-html="data.nameMarkup">

                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <input type="hidden" :name="fieldName" :value="selectedId" v-if="selectedIndex > -1"/>
                            </div> 
                            
                            `,
                props: [
                        
                        "url",
                        "filteredFromSource",
                        "propertyForId",
                        "propertyForName",
                        "includeIdInList",
                        "value",
                        "initialTextValue",
                        "searchQueryKey",
                        "fieldName",
                        //to enable the functionality to fire an explicit selection event with selected player
                        //and clear the input 
                        "enableExplicitSelection"
                        


                ],
                data: function () {

                            return {
                                panelVisible:false,
                                textFieldValue:this.initialTextValue,
                                selectedIndex:-1,
                                dataListData:[],
                                filteredFromSourceData:this.filteredFromSource != null && this.filteredFromSource.toLowerCase() == "true" ? true : false,
                                includeIdInListData:this.includeIdInList != null && this.includeIdInList.toLowerCase() == "true" ? true : false,
                                enableExplicitSelectionData:this.enableExplicitSelection != null && this.enableExplicitSelection.toLowerCase() == "true"? true : false,
                                selectedId:-1,
                                selectedDataItem:null,
                            }
                },
                methods: {
                    mouseOver:function(dataIndex,dataSelected){
                        
                        this.selectOneIndexAndUnselectOthers(dataIndex);
                        
                    },
                    downArrowPressed:function(){
                            
                            this.panelVisible = true;
                            
                            if(this.selectedIndex == -1){
                                this.selectOneIndexAndUnselectOthers(0);
                            }else if(this.selectedIndex == (this.dataListData.length-1)){
                                this.selectOneIndexAndUnselectOthers(this.dataListData.length-1);
                            }else{
                                this.selectOneIndexAndUnselectOthers(this.selectedIndex+1);
                            }
                        
                    },
                    upArrowPressed:function(){
                            
                            this.panelVisible = true;
                            if(this.selectedIndex == -1){
                                this.selectOneIndexAndUnselectOthers(this.dataListData.length-1);
                            }else if(this.selectedIndex == 0){
                                this.selectOneIndexAndUnselectOthers(0);
                            }else{
                                this.selectOneIndexAndUnselectOthers(this.selectedIndex-1);
                            }
                    },
                    enterKeyPressed:function(){
                      
                        if(this.panelVisible == true){
                            this.panelVisible = false;
                        }else{
                            this.panelVisible = true;
                        }
                        
                        this.raiseExplicitSelectionEventWithSelectedDataItem();
                        
                        
                    },
                    dataOptionClicked:function(){
                        
                         this.raiseExplicitSelectionEventWithSelectedDataItem();
                        
                    },
                    closeDropDownWithoutAction:function(){
                       
                        this.panelVisible = false;
                    },
                    inputEvent:function(){
                        this.clearSelectionIfInputTextDoesntMatchSelected();
                        this.panelVisible = false;
                        _data={};
                        _data[this.searchQueryKey]=this.textFieldValue;
                        
                        var request = $.ajax({
                                
                                    url: this.url,
                                    data:_data,
                                    mimeType: "application/json",
                                    dataType:"json",
                                    method: "GET",
                                    success:function(msg){
                                               
                                               this.dataListData = msg;
                                               this.processDataList(msg)
                                               this.panelVisible = true;
                                               

                                    }.bind(this),

                                    error: function(jqXHR, textStatus ) {
                                            console.log(textStatus);  



                                    }.bind(this)
                        });
                    },
                    selectOneIndexAndUnselectOthers:function(indexToSelect){
                        for(x=0; x < this.dataListData.length; x++){
                                if(x != indexToSelect){
                                    this.dataListData[x].selected = false;
                                }else{
                                    this.dataListData[x].selected = true;
                                    this.selectedIndex = indexToSelect;
                                    this.textFieldValue = this.dataListData[x][this.propertyForName];
                                    this.raiseInputEventWithNewSelectedId(this.dataListData[x]);
                                }
                        }
                        
                    },
                    clearSelectionIfInputTextDoesntMatchSelected:function(){
                        if(this.dataListData[this.selectedIndex] != null && this.dataListData[this.selectedIndex][this.propertyForName] != this.textFieldValue){
                            this.selectedIndex = -1;
                            this.raiseInputEventWithNewSelectedId(null);
                        }
                    },
                    processDataList:function(){
                            var autoSelectionMade = false;
                            for(x=this.dataListData.length -1 ; x >= 0; x--){
                                if(!this.filteredFromSourceData){
                                    if(this.dataListData[x][this.propertyForName].match(new RegExp(this.textFieldValue, 'gi')) == null){
                                        this.dataListData.splice(x,1);
                                        continue;
                                    }
                                }
                                this.dataListData[x].nameMarkup = this.processName(this.dataListData[x][this.propertyForId],this.dataListData[x][this.propertyForName]);
                                if(!autoSelectionMade && (this.dataListData[x][this.propertyForName].toLowerCase() == this.textFieldValue.toLowerCase())){
                                    
                                    this.dataListData[x].selected = true;
                                    this.textFieldValue = this.dataListData[x][this.propertyForName];
                                    this.raiseInputEventWithNewSelectedId(this.dataListData[x]);
                                    autoSelectionMade = true;
                                }else{
                                    this.dataListData[x].selected = false;
                                }
                                
                            }
                            //return this.dataListData;

                    },
                    processName:function(id,name){
                        name = name.replace(new RegExp(this.textFieldValue, 'gi'), function myFunction(x){return "<b>"+x+"</b>";}) ;
                        if(this.includeIdInListData){
                            name += " - "+"<span>"+id+"</span>";
                        }
                        return name;
                    },
                    returnSeletedIndexFromDataList:function(){
                        for(x=0; x < this.dataListData.length; x++){
                                if(this.dataListData[x].selected == true){
                                   return x;
                                }
                        }
                        return -1;
                    },
                    //input event to facilitate v-model directive
                    raiseInputEventWithNewSelectedId:function(newSelectedDataItem){
                        if(newSelectedDataItem != null){
                            selectedId = newSelectedDataItem[this.propertyForId];
                           
                        }else{
                            selectedId = -1;
                           
                        }
                        this.selectedDataItem = newSelectedDataItem;
                        this.selectedId = selectedId;
                        this.$emit('input',selectedId);
                        
                        
                    },
                    //explicit selection: to indicate that the user has pressed enter or clicked an item
                    raiseExplicitSelectionEventWithSelectedDataItem:function(){
                        //console.log("Explicit Selection Made");
                        if(this.enableExplicitSelectionData && this.selectedDataItem != null){
                             this.selectedIndex = -1;
                             this.textFieldValue = "";
                             var selectedDataItemTemp = JSON.parse(JSON.stringify(this.selectedDataItem));
                             delete selectedDataItemTemp.nameMarkup;
                             this.$emit('explicit-selection',selectedDataItemTemp);
                        }
                       
                        
                    },
                    
                },

        });
        
       
    </script>
