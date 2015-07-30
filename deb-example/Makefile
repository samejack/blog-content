PACKAGE_NAME="package-name"
VERSION=1.0.0.0

all ::
	@echo "[INFO] Create debian package file (control)."
	rm -rf ./${PACKAGE_NAME}/DEBIAN/control ;\
	cp ./${PACKAGE_NAME}/DEBIAN/control.sample ./${PACKAGE_NAME}/DEBIAN/control ;\
	SIZE=`du -sx --exclude DEBIAN "${PACKAGE_NAME}/DEBIAN/" | awk '{print $$1}'` ;\
	sed -i "s/Installed-Size: 0/Installed-Size: $${SIZE}/g" "./${PACKAGE_NAME}/DEBIAN/control" ;\
	sed -i 's/Version: 0.0.0-0/Version: ${VERSION}/g' "./${PACKAGE_NAME}/DEBIAN/control" ;\
	cat ./${PACKAGE_NAME}/DEBIAN/control
	@echo "[INFO] Build Deb Package."
	mkdir -p ./dest
	rm -rf "./dest/${PACKAGE_NAME}_${VERSION}.deb"
	dpkg -b "./${PACKAGE_NAME}" "./dest/${PACKAGE_NAME}_${VERSION}.deb"

install ::
	dpkg -i "./dest/${PACKAGE_NAME}_${VERSION}.deb"
