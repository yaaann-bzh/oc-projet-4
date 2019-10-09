<section class="container pt-5">
    <div class="row">
        <div class="col-12 offset-md-1 col-md-10">
            <h1 class="h2">Redaction</h1>
            <form method="post" action="" class="mb-3">
                <div class="form-group">
                    <label for="title">Titre :</label>
                    <input class="form-control" type="text" name="title" id="title" autofocus required>
                </div>
                <div class="form-group">
                    <label for="content">Texte :</label>
                    <textarea name="content" id="content" cols="30" rows="20"></textarea>
                </div>

                <input type="submit" class="btn btn-primary" value="Publier">
            </form>
        </div>
    </div>    

    <script src="https://cdn.tiny.cloud/1/yyqsfdko2tg7akueyctfkasll6yienujy96w06tbyhvuuzlq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({selector:'textarea'});</script>

</section>