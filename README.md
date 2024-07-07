# Get latest Assets

An API to always get the latest assets from a github release.

## Usage

```bash
curl -LO https://example.com/github/:owner/:repo/:asset-name
```

`:asset-name` can contain the following placeholders:

* `%major%` for the major part of the version number
* `%minor%` for the minor part of the version number
* `%patch%` for the patch part of the version number
* `%preRelease%` for the pre-release part of the version number
* `%build%` for the build part of the version number
* `%version%` for the full version ID as specified by the release.
 
## Roadmap

* Include latest assets from GitLab
* Include latest assets from BitBucket
* Include latest releases from Atlassian-Products… (That would be scratching my own itch…)
