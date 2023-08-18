<?php


class HtmlOutput
{
    public function render(array $priceData, ?string $errorMessage): void
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8"/>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
                  crossorigin="anonymous">
            <link href="assets/css/style_form.min.css" rel="stylesheet"/>
            <link href="assets/css/site.css" rel="stylesheet"/>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        </head>
        <body>
        <div class="container">
            <div class="row row-body">
                <div class="col-3">
                    <span style="text-align: center">Форма обратной связи</span>
                    <i class="bi bi-activity"></i>
                </div>
                <div class="col-9">
                    <form action="" id="form" method="post">
                        <label class="form-label" for="product">Выберите тариф:</label>
                        <select class="form-select" name="product" id="product">
                            <option value="10">Базовый тариф</option>
                            <option value="1000">Дневной тариф</option>
                            <option value="200">Почасовой тариф</option>
                            <option value="4">Студенческий</option>
                        </select>

                        <label for="customRange1" class="form-label">Количество километров:</label>
                        <input type="text" name="distance" class="form-control" id="km" min="1">

                        <label for="customRange1" class="form-label">Сколько планируете времени:</label>
                        <input type="text" name="time" class="form-control" id="time" min="1">

                        <label for="customRange1" class="form-label">Ваш возраст:</label>
                        <input type="text" name="age" class="form-control" id="age" min="18">

                        <label for="customRange1" class="form-label">Дополнительно:</label>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="check1" id="flexCheckChecked1">
                            <label class="form-check-label" for="flexCheckChecked1">
                                Дополнительный водитель
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="check2" id="flexCheckChecked2">
                            <label class="form-check-label" for="flexCheckChecked1">
                                Дополнительный WIFI
                            </label>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Рассчитать</button>

                        <?php if ($errorMessage !== null) { ?>
                            <p style="color: red;"><?php echo $errorMessage; ?></p>
                        <?php } ?>

                        <div class="col-6">
                            <p class="form-label">Итоговая цена:
                                <?php if ($errorMessage) {
//                                    echo htmlspecialchars($priceData['result'] == 0, ENT_QUOTES, 'UTF-8');
                                    echo $priceData['result'] === 0;
                                } else {
                                    echo htmlspecialchars($priceData['result'], ENT_QUOTES, 'UTF-8');
                                } ?>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
}