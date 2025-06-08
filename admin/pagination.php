<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <?php for($num = 1; $num <= $total_pages; $num++){?>
        <?php if($num != $current_page){?>
    <li class="page-item"><a class="page-link" href="?per_page<?= $item_per_page?>&page=<?= $num ?>"><?= $num ?></a></li>
    <?php
        }else{ ?>
        <strong class="current-page page-link"><?= $num ?></strong>
    <?php    
    }
    ?>
    <?php
    }
    ?>
  </ul>
</nav>