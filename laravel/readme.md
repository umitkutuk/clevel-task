 ![alt text][logo] 

[logo]: https://clevel.team/wp-content/uploads/2019/07/logo-web-225.png "Clevel CTO as a service"


### Uygulama Hakkında
  Çalışanlarının notlarını yazması için kullanmakta olduğu eski uygulamayı modern bir uygulama haline getiriyoruz.
 
  Yazılım ekibi olarak uygulamanın gelecekte yine yavaşlamaması için kayıtlı notları cache mekanizması ile sunmaya karar verdik. -*Daha sonra farklı alternatifler kullanma şansımız var*- ancak, başlangıç olarak Redis kullanmayı tercih ettik. Sistemci arkadaşlarımız ise; gerektiğinde cache mekanizmasını kaynak kodu değiştirmeden açıp kapatmak istediklerini söylediler.
  
  Uygulamanın arayüzünü front-end geliştirici arkadaşlarımız yazdı. Back-end developer olarak bizim görevimiz; yazılmış olan uygulamanın JSON uygulama programlama arayüzünü PHP 5.6+ ile geliştirmek.
  
### Gereksinimler:
  
#### Sistem Gereksinimleri:
  - Laravel 5.1+
  - MySQL, Redis
 
#### Uygulama Gereksinimleri:
##### Routes:
  Front-end uygulaması, HTTP Protokü üzerinden sırası ile şu istekleri gönderecektir. 
 
      1- [GET|HEAD]      {api_adresi}/note
      2- [GET|HEAD]      {api_adresi}/note/create
      3- [POST]          {api_adresi}/note
      4- [GET|HEAD]      {api_adresi}/note/{note}
      5- [GET|HEAD]      {api_adresi}/note/{note}/edit
      6- [PUT]           {api_adresi}/note/{note}
      8- [DELETE]        {api_adresi}/note/{note}
  
##### Request & Response Structures:
  HTTP POST ve HTTP PUT metodlarında arayüz tarafından gönderilen not prototipi şu aşağıdaki gibidir:
  
```JSON
{
  "name": "Lorem Ipsum",
  "content": "Lorem ipsum dolor sit amet.",
  "tags": [
    "foo", "bar", "baz"
  ]
}
```
 
  HTTP GET metodlarında ise, API'dan gelen veri şu şekilde olmalıdır:
```JSON
{
  "name": "Lorem Ipsum",
  "content": "Lorem ipsum dolor sit amet.",
  "created_at": "2016-10-24T15:25:43.511Z",
  "updated_at": "2016-10-24T15:25:43.511Z",
  "tags": [{
    "id": 1,
    "name": "foo"
  }, {
    "id": 2,
    "name": "bar"
  }]
}
```

###Docker Compose Dosyası(Opsiyonel)

Docker Compose ile uygulamalarınızı oluşturabilirsiniz, [docker compose](https://docs.docker.com/compose/ "Docker Compose")
altyapınızı kurma ve sürümlendirme işlemini basitleştirir.

Eğer Docker kullanmak istiyorsanız terminalden Laradoc klasörü içerisinde iken aşağıdaki kodu çalıştırmanız yeterli olacaktır.

```console
docker-compose up -d nginx mariadb redis workspace
```

###API Kullanımı
1-[GET|HEAD]      {api_adresi}/note

Notları listelemek için kullanabileceğiniz api adresidir. İsterseniz bu alanda sayfalama yapabilirsiniz.
Örnek:

```
{api_adresi}/note?page=1 // İlk 30 kayıdı çeker
{api_adresi}/note?page=2 // Sonraki 30 kaydı çeker
```

     
2- [GET|HEAD]      {api_adresi}/note/create
Not oluştururken tag alanı zorunlu alandır bundan dolayı ilk önce tagları çekmeniz gerekecektir.

3- [POST]          {api_adresi}/note
Yeni bir not yaratmak için kullanacağınız url 'dir. 

Alanlar ve Açıklamaları
```
name => required|string|max:50,
content => required|string
tags: required|numeric|array
```

Örnek(tag)
```
tags[] = [1,2,3]
```

4- [GET|HEAD]      {api_adresi}/note/{note}
İstemiş olduğunuz notun id numarasını göndererek ilişkili olduğu tags lar ile birlikte alabilirsiniz.
      
5- [GET|HEAD]      {api_adresi}/note/{note}/edit
      
Güncellemek istediğiniz notu id numarası ile göndererek ilgili not ve ona ait tagları cekebilirsiniz.

JSON objesinde gelen verinin içerisinde tags verileri de mevcuttur.
```
[
          'note' => {},
          'tags' => [{}]
      ]
```

6- [PUT]           {api_adresi}/note/{note}
Bir notu güncellemek için kullanacağınız url 'dir.

```
name => required|string|max:50,
content => required
tags: required|numeric|array
```

Örnek(tag)
```
tags[] = [1,2,3]
```

8- [DELETE]        {api_adresi}/note/{note}
İstediğiniz notu bu url i kullanarak silebilirsiniz.  
```
{note} => required|numeric
```

###Cache Mekanizmasi

Sistemci arkadaşlarımız; gerektiğinde cache mekanizmasını 
kaynak kodu değiştirmeden açıp kapatmak isteyebilirler. Bu için yapılması gereken işlem terminalden cache durumunu değiştirmek olacaktır. 

Bu seneryoda cache:status sadece **true**, **false** değerlerini alabilmektedir. Harici bir durumda hata ile karşılaşılacaktır.

#####Örnek Terminal Kodu ve Çıktısı:

```console
$ php artisan cache:status true

 Do you wish to continue? [yes|no] (yes/no) [no]:
 > yes

Successfully
```

###Kaynaklar

1. https://docs.docker.com/
1. https://laradock.io/documentation/
1. https://github.com/laravel/laravel
1. https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose
1. https://laravel.com/docs/5.8/artisan
1. https://www.markdownguide.org/basic-syntax/
