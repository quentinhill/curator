#!/bin/sh

#
# Fuck yeah!
# 

# Set up the environment. Respect $VERSION if it's set.

    set -e
    CURATOR_ROOT="$HOME/Library/Application Support/Curator"
    CURATOR_BIN="$CURATOR_ROOT/Current/bin"
    [[ -z "$VERSION" ]] && VERSION=0.1alpha
    
    echo "*** Installing Curator $VERSION..."

# Create the Curator directory structure if it doesn't already exist.

    mkdir -p "$CURATOR_ROOT/Versions"


# If the requested version of Curator is already installed, remove it first.

      cd "$CURATOR_ROOT/Versions"
      rm -rf "$CURATOR_ROOT/Versions/$VERSION"


# Download the requested version of Curator and unpack it.

      curl -s http://curator.distribution/$VERSION.tar.bz2 | tar xjf -


# Update the Current symlink to point to the new version.

      cd "$CURATOR_ROOT"
      rm -f Current
      ln -s Versions/$VERSION Current
      
      echo "*** Installed"