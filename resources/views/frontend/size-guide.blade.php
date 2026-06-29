<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff">
    <title>Size Guide - Vastra Express</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="size-page">
    <div class="site-wrap">
        <header class="simple-page-header"><a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('frontend.home') }}"><i class="bi bi-chevron-left"></i></a>
            <h1>Size Guide</h1>
        </header>
        <main>
            <div class="size-tabs"><b data-size-gender="men" role="button" tabindex="0" aria-pressed="true">Men</b><span data-size-gender="women" role="button" tabindex="0" aria-pressed="false">Women</span><span data-size-gender="kids" role="button" tabindex="0" aria-pressed="false">Kids</span></div>
            <div class="unit-tabs"><b data-size-unit="inch" role="button" tabindex="0" aria-pressed="true">Inch</b><span data-size-unit="cm" role="button" tabindex="0" aria-pressed="false">CM</span></div>
            <h2>T-Shirts &amp; Shirts</h2>
            <table data-size-chart="tops">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Chest</th>
                        <th>Shoulder</th>
                        <th>Length</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="size-expand" data-size-toggle="pants-jeans" role="button" tabindex="0" aria-expanded="false">Pants &amp; Jeans <i class="bi bi-chevron-down"></i></div>
            <div class="size-panel" id="pants-jeans" hidden>
                <table data-size-chart="pants">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Waist</th>
                            <th>Hip</th>
                            <th>Length</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="size-expand" data-size-toggle="bottom-wear" role="button" tabindex="0" aria-expanded="false">Bottom Wear <i class="bi bi-chevron-down"></i></div>
            <div class="size-panel" id="bottom-wear" hidden>
                <table data-size-chart="bottoms">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Waist</th>
                            <th>Hip</th>
                            <th>Inseam</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        var currentGender = 'men';
        var currentUnit = 'inch';

        var sizeCharts = {
            men: {
                tops: [
                    ['S', 36, 17, 26],
                    ['M', 40, 17.5, 26],
                    ['L', 42, 18.5, 28],
                    ['XL', 44, 19, 29],
                    ['XXL', 46, 20, 30]
                ],
                pants: [
                    ['28', 28, 36, 39],
                    ['30', 30, 38, 40],
                    ['32', 32, 40, 41],
                    ['34', 34, 42, 42],
                    ['36', 36, 44, 42]
                ],
                bottoms: [
                    ['S', '28-30', '36-38', 29],
                    ['M', '30-32', '38-40', 30],
                    ['L', '32-34', '40-42', 31],
                    ['XL', '34-36', '42-44', 32],
                    ['XXL', '36-38', '44-46', 32]
                ]
            },
            women: {
                tops: [
                    ['XS', 32, 14, 23],
                    ['S', 34, 14.5, 24],
                    ['M', 36, 15, 25],
                    ['L', 38, 15.5, 26],
                    ['XL', 40, 16, 27]
                ],
                pants: [
                    ['26', 26, 36, 38],
                    ['28', 28, 38, 39],
                    ['30', 30, 40, 40],
                    ['32', 32, 42, 41],
                    ['34', 34, 44, 41]
                ],
                bottoms: [
                    ['XS', '24-26', '34-36', 28],
                    ['S', '26-28', '36-38', 29],
                    ['M', '28-30', '38-40', 30],
                    ['L', '30-32', '40-42', 31],
                    ['XL', '32-34', '42-44', 31]
                ]
            },
            kids: {
                tops: [
                    ['2-3Y', 22, 9, 15],
                    ['4-5Y', 24, 10, 17],
                    ['6-7Y', 26, 11, 19],
                    ['8-9Y', 28, 12, 21],
                    ['10-11Y', 30, 13, 23]
                ],
                pants: [
                    ['2-3Y', 20, 24, 20],
                    ['4-5Y', 21, 26, 23],
                    ['6-7Y', 22, 28, 26],
                    ['8-9Y', 24, 30, 29],
                    ['10-11Y', 26, 32, 32]
                ],
                bottoms: [
                    ['2-3Y', '19-20', '23-24', 13],
                    ['4-5Y', '20-21', '25-26', 15],
                    ['6-7Y', '21-22', '27-28', 17],
                    ['8-9Y', '23-24', '29-30', 19],
                    ['10-11Y', '25-26', '31-32', 21]
                ]
            }
        };

        function convertValue(value) {
            if (currentUnit === 'inch') {
                return value;
            }

            if (typeof value === 'number') {
                return Math.round(value * 2.54 * 10) / 10;
            }

            return value.split('-').map(function (part) {
                return Math.round(Number(part) * 2.54);
            }).join('-');
        }

        function renderCharts() {
            document.querySelectorAll('[data-size-chart]').forEach(function (table) {
                var type = table.dataset.sizeChart;
                var tbody = table.querySelector('tbody');
                var rows = sizeCharts[currentGender][type];

                tbody.innerHTML = rows.map(function (row) {
                    return '<tr>' + row.map(function (cell, index) {
                        return '<td>' + (index === 0 ? cell : convertValue(cell)) + '</td>';
                    }).join('') + '</tr>';
                }).join('');
            });
        }

        function setActive(selector, activeElement) {
            document.querySelectorAll(selector).forEach(function (element) {
                var replacement = element === activeElement ? 'b' : 'span';
                var next = document.createElement(replacement);

                Array.prototype.slice.call(element.attributes).forEach(function (attribute) {
                    next.setAttribute(attribute.name, attribute.value);
                });

                next.innerHTML = element.innerHTML;
                next.setAttribute('aria-pressed', String(element === activeElement));
                element.replaceWith(next);
            });

            bindTabs();
        }

        function bindTabs() {
            document.querySelectorAll('[data-size-gender]').forEach(function (tab) {
                tab.onclick = function () {
                    currentGender = tab.dataset.sizeGender;
                    setActive('[data-size-gender]', tab);
                    renderCharts();
                };

                tab.onkeydown = function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        tab.click();
                    }
                };
            });

            document.querySelectorAll('[data-size-unit]').forEach(function (tab) {
                tab.onclick = function () {
                    currentUnit = tab.dataset.sizeUnit;
                    setActive('[data-size-unit]', tab);
                    renderCharts();
                };

                tab.onkeydown = function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        tab.click();
                    }
                };
            });
        }

        document.querySelectorAll('[data-size-toggle]').forEach(function (toggle) {
            function openPanel() {
                var panel = document.getElementById(toggle.dataset.sizeToggle);
                var icon = toggle.querySelector('i');

                if (!panel) {
                    return;
                }

                var isOpen = !panel.hidden;
                panel.hidden = isOpen;
                toggle.setAttribute('aria-expanded', String(!isOpen));

                if (icon) {
                    icon.classList.toggle('bi-chevron-down', isOpen);
                    icon.classList.toggle('bi-chevron-up', !isOpen);
                }
            }

            toggle.addEventListener('click', openPanel);
            toggle.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openPanel();
                }
            });
        });

        bindTabs();
        renderCharts();
    </script>
</body>

</html>
