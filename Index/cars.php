<div class="row">
    <h2>Автомобильный ряд</h2>
<?php
    
                        require_once '../Classes/Product.php';
                        $product = new Product();
                        $result = $product->ShowProducts();
                        if($result){
                            print "<table class='table table-hover'>
                                    <thead class='thead-default'>
                                        <th class='d-none'></th>
                                        <th>Фото</th>
                                        <th>Название</th>
                                        <th>Основные</th>
                                        <th>Доп. опции</th>
                                        <th>Цена</th>
                                        <th></th>
                                    </thead>
                                    <tbody>";
                            require_once '../wideimage/lib/wideimage.php';
                            for ($i=0; $i < count($result); $i++) { 
                                $img = base64_decode($result[$i]->Photo);
                                $img = WideImage::load($img);
                                $img = $img->resize(180, 120);
                                $img = base64_encode($img);
                                print "<tr>
                                            <td class='d-none'>{$result[$i]->id}</td>
                                            <td><img src=\"data:image/jpg;base64,{$img}\"></td>
                                            <td>{$result[$i]->Brand} {$result[$i]->Model}</td>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>Кузов: {$result[$i]->Type}</li>
                                                    <li>КПП: {$result[$i]->Transmission}</li>
                                                    <li>Топливо: {$result[$i]->Oil}</li>
                                                    <li>Привод: {$result[$i]->Control}</li>
                                                </ul>
                                            </td>";
                                if ($result[$i]->Options ?? '') {
                                   print "<td><ul>";
                                   for ($j=0; $j < count($result[$i]->Options); $j++) { 
                                        print "<li>{$result[$i]->Options[$j]->Title}</li>";
                                    }
                                    print "</ul></td>";
                                } else {
                                    print "<td>Отсутствуют</td>";
                                }
                                print "<td>{$result[$i]->Price} руб./ч</td>
                                        <td>
                                            <form method='POST'>
                                                <div class='input-group mb-3'>
                                                    <input type='text' class='form-control hours' placeholder='Укажите кол-во часов' aria-label='Укажите кол-во часов' aria-describedby='basic-addon2'>
                                                    <div class='input-group-append'>
                                                        <button class='btn btn-default btn-rent' type='button'>В аренду</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        </tr>";
                            }
                            print "</tbody></table>";
                        } else {
                            echo('<div class="alert alert-danger">Вы не создали ни одного товара');                            
                        }
?>

</div>