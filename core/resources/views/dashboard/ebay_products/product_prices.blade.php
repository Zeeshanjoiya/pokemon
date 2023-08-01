<tr class="child-<?= $product_id ?>">
    <td colspan="10">
        <table style="width: 100%; background: white;">
            <thead style="background: black; color: white;">
                <tr>
                    <th>Current Price</th>
                    <th>Last Sold Price</th>
                    <th>Last Sold Date</th>
                    <th>Total Sold Quantity</th>
                </tr>
            </thead>


            <tbody>
                @if(!empty($prices))
                
                <?php foreach($prices as $price) { ?>
                    <tr>
                        <td><?= $price->currency.' '.$price->current_price?></td>
                        <td><?=  isset($price->product_last_sold_price) ? $price->currency.' '.$price->product_last_sold_price : '-' ?></td>
                        <td><?= isset($price->product_last_sold_date) ? $price->product_last_sold_date : '-' ?></td>
                        <td><?= isset($price->total_sold_quantity) ? $price->total_sold_quantity : '-' ?></td>
                    </tr>
                <?php } ?>

                @else

                <tr>
                    <td colspan="5"> Data not found !!!</td>
                </tr>

                @endif
            </tbody>
        </table>
    </td>
</tr>