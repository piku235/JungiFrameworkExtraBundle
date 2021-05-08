# JungiFrameworkExtraBundle

Just like the `SensioFrameworkExtraBundle` this bundle adds extra features on top of existing in the Symfony `FrameworkBundle`. The main aim of this bundle is to facilitate the request/response operations.

[![Continuous Integration](https://github.com/piku235/JungiFrameworkExtraBundle/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/piku235/JungiFrameworkExtraBundle/actions/workflows/continuous-integration.yml)

Attributes (aka annotations):

* **RequestBody** - Maps/converts the request body content/parameters to the controller method argument.
* **RequestHeader** - Converts a request header to the controller method argument.
* **RequestCookie** - Converts a request cookie to the controller method argument.
* **RequestParam** - Converts a request body parameter to the controller method argument.
* **QueryParam** - Converts a request query parameter to the controller method argument.
* **QueryParams** - Converts the request query parameters to the controller method argument.
* **ResponseBody** - Maps the controller method result to an appropriate entity response.

## Quick insight

### @Annotation

```php
namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Jungi\FrameworkExtraBundle\Annotation\QueryParams;
use Jungi\FrameworkExtraBundle\Annotation\RequestBody;
use Jungi\FrameworkExtraBundle\Annotation\ResponseBody;
use Jungi\FrameworkExtraBundle\Annotation\QueryParam;
use Jungi\FrameworkExtraBundle\Annotation\RequestParam;

/**
 * @Route("/users")
 */
class UserController
{
    /**
     * @Route("/{userId}/residential-address", methods={"PATCH"})
     * @RequestBody("data")
     */
    public function changeResidentialAddress(string $userId, UserResidentialAddressData $data)
    {
        // ..
    }

    /**
     * @Route("/{userId}/files/{fileName}", methods={"PUT"})
     * @RequestBody("file")
     */
    public function uploadFile(string $userId, string $fileName, UploadedFile $file)
    {
        // ..
    }

    /**
     * @Route("/{userId}/avatar", methods={"PATCH"})
     *
     * @RequestParam("file")
     * @RequestParam("title")
     */
    public function replaceAvatar(string $userId, UploadedFile $file, string $title)
    {
        // ..
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @QueryParam("limit")
     * @QueryParam("offset")
     */
    public function getUsers(?int $limit = null, ?int $offset = null)
    {
        // ..
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @QueryParams("filterData")
     * @ResponseBody
     */
    public function filterUsers(FilterUsersDto $filterData)
    {
        // ..
        /** @var UserData[] $filteredUsers */
        return $filteredUsers;
    }
}
```

### #[Attribute]

```php
namespace App\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Jungi\FrameworkExtraBundle\Attribute\QueryParams;
use Jungi\FrameworkExtraBundle\Attribute\RequestBody;
use Jungi\FrameworkExtraBundle\Attribute\ResponseBody;
use Jungi\FrameworkExtraBundle\Attribute\QueryParam;
use Jungi\FrameworkExtraBundle\Attribute\RequestParam;

#[Route('/users')]
class UserController
{
    #[Route('/{userId}/residential-address', methods: ['PATCH'])]
    public function changeResidentialAddress(string $userId, #[RequestBody] UserResidentialAddressData $data)
    {
        // ..
    }

    #[Route('/{userId}/files/{fileName}', methods: ['PUT'])]
    public function uploadFile(string $userId, string $fileName, #[RequestBody] UploadedFile $file)
    {
        // ..
    }

    #[Route('/{userId}/avatar', methods: ['PATCH'])]
    public function replaceAvatar(string $userId, #[RequestParam] UploadedFile $file,  #[RequestParam] string $title)
    {
        // ..
    }

    #[Route('', methods: ['GET'])]
    public function getUsers(#[QueryParam] ?int $limit = null, #[QueryParam] ?int $offset = null)
    {
        // ..
    }

    #[Route('', methods: ['GET'])]
    #[ResponseBody]
    public function filterUsers(#[QueryParams] FilterUsersDto $filterData)
    {
        // ..
        /** @var UserData[] $filteredUsers */
        return $filteredUsers;
    }
}
```

## Installation

```text
composer require jungi/framework-extra-bundle
```

If you're using in your project symfony flex you're already done! Otherwise, you need to enable the bundle manually.

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Jungi\FrameworkExtraBundle\JungiFrameworkExtraBundle(),
    );
    // ...
}
```

## Documentation

[click me](https://piku235.gitbook.io/jungiframeworkextrabundle)

