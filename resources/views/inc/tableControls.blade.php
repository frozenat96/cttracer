
  <ul class="pagination">
    <li>
      <span>page : </span>
      <a href="javascript:void(0);" data-bind="text: page" style="text-decoration: none;color:black;"></a>
    </li>    
    <li>
      <a title="First Page" href="javascript:void(0);" data-bind="click: goToFirstPage"><span class="pageButtons"><<</span></a>
    </li>
    <li>
      <a title="Previous Page" href="javascript:void(0);" data-bind="click: goToPreviousPage"><span class="pageButtons"><</span></a>
    </li>
      <!-- ko foreach: pages -->
    <li>
      <a href="javascript:void(0);" data-bind="click: $parent.goToPage.bind($data)" >
        <span class="pageButtons" data-bind="text:$data"></span>
      </a>
    </li>
      <!-- /ko -->
    <li>
      <a title="Next Page" href="javascript:void(0);" data-bind="click: goToNextPage"><span class="pageButtons">></a>
    </li>
    <li>
      <a title="Last Page" href="javascript:void(0);" data-bind="click: goToLastPage"><span class="pageButtons">>></a>
    </li>
    <li>
      <span>Go to page : </span>
      <input type="text" name="pageSearch" id="pageSearch" maxlength="25" style="width:100px;">
    </li>
    <li>
      <a href="javascript:void(0);" data-bind="click: jumpToPage"><span>Go</span></a>
    </li>
  </ul>