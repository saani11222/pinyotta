<div id="filterPopupOverLay">
    <div class="outerlayer">
        <div class="filterPopup">
            <div class="row filterPopupTop">
                <div class="col-11 pd-0">
                    <div class="filter_text">Filters</div>
                </div>
                <div class="col-1 pd-0">
                    <div>
                        <img onclick="clearAll('cross')" style="cursor: pointer;" height="12px" width="12px"
                            src="{{ asset('assets/img/x.png') }}" alt="">
                    </div>
                </div>
            </div>
            <form id="selected_filters">
                <div class="popupMain-content">

                    {{-- cusine filter --}}
                    <div class="cusine_filter_list">
                        <div style="font-size: 16px;font-weight:500;padding-bottom:10px">Cusine</div>
                        <div class="cusine_filter">
                            @php
                            $filters =
                            ['American','Barbecue','Chinese','French','Hamburger','Indian','Italian','Japanese','Mexican','Pizza','Seafood','Steak','Sushi','Thai'];
                            @endphp
                            @foreach ( $filters as $filter )

                            <label class="filters" for="{{$filter}}" style="cursor: pointer;">
                                <input class="visually-hidden" type="checkbox" name="cusine[]" value="{{$filter}}"
                                    id="{{$filter}}" onclick="selectFilter(this)">
                                {{$filter}}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    {{-- vibe filter --}}
                    <div class="vibe_filter_list" style="padding-top: 0px">
                        <div style="font-size: 16px;font-weight:500;padding-bottom:10px">Vibe</div>
                        <div class="cusine_filter">
                            @php
                            $filters = ['Casual','Cozy','Fancy','Trendy','Romantic'];
                            @endphp
                            @foreach ( $filters as $filter )
                            <label class="filters" for="{{$filter}}" style="cursor: pointer;">
                                <input class="visually-hidden" type="checkbox" name="vibe[]" value="{{$filter}}"
                                    id="{{$filter}}" onclick="selectFilter(this)">
                                {{$filter}}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
            <div class="popup-bottom-content">
                <div style="    display: flex;justify-content: space-between;padding: 20px;align-items:center;">
                    <div id="clear_all" onclick="clearAll()">Clear all</div>
                    <div id="apply_filters" style="cursor: default">Apply</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function selectFilter (el) {
                let checkedElement = $('input[type="checkbox"]:checked');
                let checkedCount = checkedElement.length;
                if ($(el).prop('checked')) {
                    $(el).parent('label').addClass('selected_filter');                 
                } else {
                    $(el).parent('label').removeClass('selected_filter');                
                }
                if(checkedCount > 0){
                    $('#apply_filters').css('cursor','pointer');
                    $('#apply_filters').addClass('apply_filter');
                }else{
                    $('#apply_filters').css('cursor','default');
                    $('#apply_filters').removeClass('apply_filter');
                }
            }
          
    function clearAll(param){
                let checkedElement = $('input[type="checkbox"]:checked');
                let checkedCount = checkedElement.length;
            if(param == 'cross'){
                $('#filterPopupOverLay').fadeOut(300);
                if(!$('#filterCount').hasClass('active_filter') || (checkedCount == 0)){
                    $('label').each(function () {
                        $(this).find('input[type="checkbox"]').prop('checked', false);
                        $(this).removeClass('selected_filter');
                        $('#apply_filters').css('cursor','default');
                        $('#apply_filters').removeClass('apply_filter');
                    });
                        $('#filterCount').html('');
                        $('#filterCount').removeClass('active_filter'); 
                    }
            }else{
                $('label').each(function () {
                    $(this).find('input[type="checkbox"]').prop('checked', false);
                    $(this).removeClass('selected_filter');
                    $('#apply_filters').css('cursor','default');
                    $('#apply_filters').removeClass('apply_filter');
                });
                $('#filterCount').html('');
                $('#filterCount').removeClass('active_filter');
            }       
    }
    

</script>
