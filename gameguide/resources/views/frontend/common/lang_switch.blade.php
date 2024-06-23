<div class="box box-primary">
    <div class="box-body">

        <form method="post" action="{{url ('lang-switch')}}" id="language-form">
            <div class="form-group">
                @csrf
                <select id="language" class="form-control" name="language" required onchange="this.form.submit()">
                    <option value="en" {{app()->getLocale() === 'en' ? 'selected' : ''}}>English
                    </option>
                    <option value="ar" {{app()->getLocale() === 'ar' ? 'selected' : ''}}>Arabic
                    </option>
                </select>
            </div>

        </form>
    </div>
</div>