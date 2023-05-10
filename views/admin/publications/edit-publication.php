<?php
    include '../../../db/db.php';
    include '../../../includes/admin/templates/header.php';
    if (isset($_POST['edit'])) {
?>
<link rel="stylesheet" href="../../../css/index.css">
<link rel="stylesheet" href="new-publication.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
<?php
    include '../../../includes/admin/templates/navbar.php';
?>
    <main>
        <div class="header">
            <h1 class="title">Publication Edit</h1>
        </div>
        <section>
            <div class="container">
            <?php
                $publicationID = $_POST['row_id'];
                $fetchdata = pg_query($conn, "SELECT * FROM table_publications WHERE publication_id = '$publicationID'");
                while($row = pg_fetch_assoc($fetchdata)){
            ?>
                <form action="functionalities/button_functions/publication-edit.php" method="POST" onsubmit="return chooseOneSDG(); checkDuplicateAuthors();">
                    <div class="sub-container">
                        <div class="title">
                            <h3>Document Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="form-container">
                                <div class="form-control">
                                    <label class="pb-label" for="pb-title">Work Title</label>
                                    <input type="text" placeholder="Work Title" id="pb-title" name="title_of_paper" value="<?=$row['title_of_paper']?>" required/>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-type">Type of Document</label>
                                    <select  name="type_of_publication" id="pb-type" required>
                                        <option value="<?=$row['type_of_publication']?>" hidden><?=$row['type_of_publication']?></option>
                                        <option value="Original Article">Original Article</option>
                                        <option value="Review">Review</option>
                                        <option value="Proceedings">Proceedings</option>
                                        <option value="Communication">Communication</option>
                                        <option value="International">International</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-publisher">Publisher</label>
                                    <input type="text" id="pb-publisher" name="publisher" list="pb-type-list" placeholder="Publisher" value="<?=$row['publisher']?>">
                                    <datalist id="pb-type-list">
                                        <option value="Clarivate">Clarivate</option>
                                        <option value="Silpakorn University">Silpakorn University</option>
                                    </datalist>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-research-area">Research Area</label>
                                    <input type="text" id="pb-research-area" name="research_area" placeholder="Research Area" value="<?=$row['department']?>">
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="form-control">
                                    <label class="pb-label" for="pb-college">College</label>
                                    <select name="college" class="pb-input-field" id="pb-college" required>
                                        <option value="<?=$row['college']?>" hidden><?=$row['college']?></option>
                                        <option value="Accountancy, Business, and International Hospitality">Accountancy, Business, and International Hospitality</option>
                                        <option value="Agriculture and Forestry">Agriculture and Forestry</option>
                                        <option value="Arts and Sciences">Arts and Sciences</option>
                                        <option value="Basic Education">Basic Education</option>
                                        <option value="College of Medicine">College of Medicine</option>
                                        <option value="Engineering, Architecture and Fine Arts">Engineering, Architecture and Fine Arts</option>
                                        <option value="ETEEAP">ETEEAP</option>
                                        <option value="Informatics and Computing Sciences">Informatics and Computing Sciences</option>
                                        <option value="Industrial Technology">Industrial Technology</option>
                                        <option value="Law">Law</option>
                                        <option value="Nursing, Nutrition and Dietetics">Nursing, Nutrition and Dietetics</option>
                                        <option value="Teacher Education">Teacher Education</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-campus" value="<?=$row['campus']?>">Campus</label>
                                    <select name="campus" id="pb-campus" required>
                                        <option value="<?=$row['campus']?>" hidden><?=$row['campus']?></option>
                                        <option value="Alangilan">Alangilan</option>
                                        <option value="Balayan">Balayan</option>
                                        <option value="Lemery">Lemery</option>
                                        <option value="Lipa">Lipa</option>
                                        <option value="Lobo">Lobo</option>
                                        <option value="Mabini">Mabini</option>
                                        <option value="Malvar">Malvar-JPCPC</option>
                                        <option value="Nasugbu">Nasugbu-Arasof</option>
                                        <option value="Pablo Borbon (Main I)">Pablo Borbon</option>
                                        <option value="Padre Garcia">Padre Garcia</option>
                                        <option value="Rosario">Rosario</option>
                                        <option value="San Juan">San Juan</option>
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-quarter">Quarter</label>
                                    <input type ="text" name="pb-quartile" class="pb-select-field" id="pb-quarter" value="<?=$row['quartile']?>" required></input>
                                </div>
                                <div class="form-control">
                                    <label class="pb-label" for="pb-date-published">Date Published</label>
                                    <input type="date" max="<?= date('Y-m-d'); ?>" id="pb-date-published" name="date_published" placeholder="Date Published" value="<?=$row['date_published']?>">
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="form-control">
                                    <label class="pb-label" for="sdg_no">SDG (choose at least 5):</label>
                                    <div class="checkbox-container">
                                        <?php
                                        $sdg_array = explode(",", $row['sdg_no']);
                                        ?>
                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="1" id="sdg1" onclick="limitSelection()" <?php if (in_array('1', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg1">SDG 1</label>
                                        </div>
                                        
                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="2" id="sdg2" onclick="limitSelection()" <?php if (in_array('2', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg2">SDG 2</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="3" id="sdg3" onclick="limitSelection()" <?php if (in_array('3', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg3">SDG 3</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="4" id="sdg4" onclick="limitSelection()" <?php if (in_array('4', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg4">SDG 4</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="5" id="sdg5" onclick="limitSelection()" <?php if (in_array('5', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg5">SDG 5</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="6" id="sdg6" onclick="limitSelection()" <?php if (in_array('6', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg6">SDG 6</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="7" id="sdg7" onclick="limitSelection()" <?php if (in_array('7', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg7">SDG 7</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="8" id="sdg8" onclick="limitSelection()" <?php if (in_array('8', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg8">SDG 8</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="9" id="sdg9" onclick="limitSelection()" <?php if (in_array('9', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg9">SDG 9</label>
                                        </div>

                                        <div class="checkbox-col">
                                            <input type="checkbox" name="sdg_no[]" value="10" id="sdg10" onclick="limitSelection()" <?php if (in_array('10', $sdg_array)) echo 'checked="checked"'; ?>>
                                            <label class="sdg-checkbox" for="sdg10">SDG 10</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="11" id="sdg11" onclick="limitSelection()" <?= in_array('11', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg11">SDG 11</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="12" id="sdg12" onclick="limitSelection()" <?= in_array('12', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg12">SDG 12</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="13" id="sdg13" onclick="limitSelection()" <?= in_array('13', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg13">SDG 13</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="14" id="sdg14" onclick="limitSelection()" <?= in_array('14', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg14">SDG 14</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="15" id="sdg15" onclick="limitSelection()" <?= in_array('15', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg15">SDG 15</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="16" id="sdg16" onclick="limitSelection()" <?= in_array('16', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg16">SDG 16</label>
                                        </div>

                                        <div class="checkbox-col">
                                        <input type="checkbox" name="sdg_no[]" value="17" id="sdg17" onclick="limitSelection()" <?= in_array('17', $sdg_array) ? 'checked' : ''; ?>>
                                        <label class="sdg-checkbox" for="sdg17">SDG 17</label>
                                        </div>
                                        
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="form-control">
                                    <label class="pb-label" for="pb-url">Document Url</label>
                                    <input type="url" id="pb-url" name="google_scholar_details" placeholder="Document Url" value="<?=$row['google_scholar_details']?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sub-container">
                        <div class="title">
                            <h3>Author Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="author-table-container">
                            <table id="author-tbl">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="author-tbl-body">
                                            <?php
                                            $query = "SELECT author_id, author_name FROM table_authors";
                                            $params = array();
                                            $result = pg_query_params($conn, $query, $params);


                                            $author_list = $row["authors"];

                                            $authors = explode(",", $author_list);
                                            foreach ($authors as $author) {

                                            $authorData = pg_query($conn, "SELECT author_id, author_name FROM table_authors WHERE author_id = '$author'");
                                            
                                            while($author_list_row = pg_fetch_assoc($authorData)){

                                            
                                                echo '
                                                <tr>
                                                <td class="ipa-author-field">
                                                <input list="authors" name="author_name[]"
                                                style="
                                                width: 100%;
                                                height: 50px;
                                                padding: 10px 36px 10px 16px;
                                                border-radius: 5px;
                                                border: 1px solid var(--dark-grey);"
                                                onchange="showAuthorId(this)"
                                                value="' . $author_list_row['author_name'] . '"
                                                placeholder="Author Name...">
                                                <input type="hidden" name="author_id[]" class="author-id-input" value="' . $author_list_row['author_id'] . '">
                                                </td>                                                
                                                <td class="ipa-author-field" style="text-align:center;"><button name="remove" style="height: 50px; width:3.7rem; border-radius: 5px; border: none; padding: 0 20px; background: var(--primary); color: var(--light); font-size: 25px; font-weight: 600; cursor: pointer; letter-spacing: 1px; font-weight: 600;"id="remove"><i class="fa-solid fa-xmark fa-xs"></i></button></td>
                                                </tr>';
                                            }
                                        }
                                            echo '<datalist id="authors">';
                                            while ($author_row = pg_fetch_assoc($result)) {
                                                echo '<option value="' . $author_row['author_name'] . '">' . $author_row['author_id'] . '</option>';
                                            }
                                            echo '</datalist>';
                                            ?>
                                </tbody>
                                            <td style="text-align: center;" colspan="2">
                                                <button type="button" class="add-row-btn" style="height: 50px; width: 10%;">+</button>
                                            </td>
                                            <div id="error-msg" style="display: none; color: red;">Duplicate author names are not allowed!</div>
                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="sub-container">
                        <div class="title">
                            <h3>Funding Details</h3>
                            <hr>
                        </div>
                        <div class="form-col">
                            <div class="funding-form-container">
                                <label class="funding-titles">Funding Nature</label>
                                <div class="form-control">
                                    <div class="choices">
                                        <input type="radio" name="nature_of_funding" id="funded" value="funded" <?=($row['nature_of_funding'] == 'funded') ? 'checked="checked"' : ''; ?>>
                                        <label for="funded" class="funding-choices">Funded</label>
                                    </div>
                                    <div class="choices">
                                        <input type="radio" name="nature_of_funding" id="non-funded" value="non-funded" <?=($row['nature_of_funding'] == 'non-funded') ? 'checked="checked"' : ''; ?>>
                                        <label for="non-funded" class="funding-choices">Non-funded</label>
                                    </div>
                                </div>
                            </div>
                            <h4 class="if-funded">If funded : </h4>
                            <div class="funding-form-container">
                                <label class="funding-titles" id="fund-type-label">Fund type </label>
                                <div class="form-control">
                                    <div class="choices">
                                        <input type="radio" name="funding_type" id="internal" value="internal" <?=($row['funding_type'] == 'internal') ? 'checked="checked"' : ''; ?>>
                                        <label for="internal" class="funding-choices">Internal</label>
                                    </div>
                                    <div class="choices">
                                        <input type="radio" name="funding_type" id="external" value="external" <?=($row['funding_type'] == 'external') ? 'checked="checked"' : ''; ?>>
                                        <label for="external" class="funding-choices">External</label>
                                    </div>
                                </div>
                            </div>
                            <h4 class="if-external">If external : </h4>
                            <div class="funding-form-container2">
                                <div class="form-control">
                                    <label class="pb-label" for="pb-funding-agency" id="pb-funding-label">Funding Agency</label>
                                    <input type="text"name="funding_source" class="pb-input-field" id="pb-funding-agency" placeholder="Funding Agency" value="<?=$row['funding_source']?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-footer">
                        <input type="hidden" name="pubID" value="<?=$row['publication_id']?>">
                        <input type="submit" class="submit-btn" name="updatePB" value="Submit">
                        <input type="button" class="cancel-btn" value="Cancel">
                    </div>
                </form>
                <?php
                }
                ?>
            </div>
        </section>
    </main>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>
<script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">

<script src="edit-publication.js"></script>

<?php
    include 'new-publication-js.php';
?>
</body>
<?php
    }else{
        header("Location: ./publications.php");
    }
?>