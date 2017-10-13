## 0.1.2

### Changed

- Reverted [MAGETWO-61135: [Backport] ](https://github.com/magento/magento2/commit/82a2a6fb75896235b9be30816abb4b87cd82a740) which breaks image saving completely and seems to not fix the [issue 7153](https://github.com/magento/magento2/issues/7153)

## 0.1.1

### Changed

- Corrected image processing while product repository saves
- Added possibly set media attributes down the line

## 0.1.0

### Added
- Overwritten product gallery processor to add missing media type value
- Overwritten product repository to adjust media gallery handling on product save